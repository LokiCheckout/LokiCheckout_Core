<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

use GuzzleHttp\Client;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class TokenValidator implements TokenValidatorInterface
{
    private const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    private const TIMEOUT = 10;
    private const TEST_KEY_HOSTNAME = 'testkey.google.com';

    public function __construct(
        private readonly Client $client,
        private readonly Config $config,
        private readonly RemoteAddress $remoteAddress,
        private readonly LoggerInterface $logger,
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    public function validate(string $token, string $formId): ValidationResult
    {
        $type = $this->config->getTypeFor($formId);
        if ($type === null) {
            return new ValidationResult(true);
        }

        $token = trim($token, " \f\n\r\t\v\x00");
        if ($token === '') {
            return new ValidationResult(false, $this->config->getValidationFailureMessage());
        }

        try {
            $response = $this->client->post(self::SITE_VERIFY_URL, [
                'form_params' => [
                    'secret' => $this->config->getSecretKey($type),
                    'response' => $token,
                    'remoteip' => (string)$this->remoteAddress->getRemoteAddress(),
                ],
                'timeout' => self::TIMEOUT,
            ]);

            $data = json_decode((string)$response->getBody(), true);
        } catch (Throwable $exception) {
            $this->logger->error('LokiCheckout reCAPTCHA verification failed: ' . $exception->getMessage());

            return new ValidationResult(false, $this->config->getTechnicalFailureMessage());
        }

        if (false === is_array($data)) {
            $this->logger->error('LokiCheckout reCAPTCHA verification returned an unreadable response');

            return new ValidationResult(false, $this->config->getTechnicalFailureMessage());
        }

        if (true !== ($data['success'] ?? false)) {
            return new ValidationResult(false, $this->getFailureMessage((array)($data['error-codes'] ?? [])));
        }

        if ($type === Type::V3 && (float)($data['score'] ?? 0.0) < $this->config->getScoreThreshold()) {
            return new ValidationResult(false, $this->config->getValidationFailureMessage());
        }

        if ($type === Type::V3 && isset($data['action']) && $data['action'] !== $formId) {
            return new ValidationResult(false, $this->config->getValidationFailureMessage());
        }

        if (isset($data['hostname']) && false === $this->isAllowedHostname((string)$data['hostname'])) {
            $this->logger->warning('LokiCheckout reCAPTCHA token was issued for another hostname: ' . $data['hostname']);

            return new ValidationResult(false, $this->config->getValidationFailureMessage());
        }

        return new ValidationResult(true);
    }

    /**
     * Configuration problems on our side are technical failures, everything else is the visitor
     * failing the challenge.
     *
     * @param string[] $errorCodes
     */
    private function getFailureMessage(array $errorCodes): string
    {
        $technicalErrorCodes = ['missing-input-secret', 'invalid-input-secret', 'bad-request'];

        if (array_intersect($errorCodes, $technicalErrorCodes) !== []) {
            $this->logger->error(
                'LokiCheckout reCAPTCHA verification rejected: ' . implode(', ', $errorCodes)
            );

            return $this->config->getTechnicalFailureMessage();
        }

        return $this->config->getValidationFailureMessage();
    }

    private function isAllowedHostname(string $hostname): bool
    {
        $hostname = strtolower($hostname);
        if ($hostname === self::TEST_KEY_HOSTNAME) {
            return true;
        }

        foreach ($this->storeManager->getStores() as $store) {
            foreach ([UrlInterface::URL_TYPE_LINK, UrlInterface::URL_TYPE_WEB] as $urlType) {
                foreach ([false, true] as $secure) {
                    $host = parse_url((string)$store->getBaseUrl($urlType, $secure), PHP_URL_HOST);
                    if (is_string($host) && strtolower($host) === $hostname) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
