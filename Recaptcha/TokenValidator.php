<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

use GuzzleHttp\Client;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Psr\Log\LoggerInterface;
use Throwable;

class TokenValidator implements TokenValidatorInterface
{
    private const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    private const TIMEOUT = 10;

    public function __construct(
        private readonly Client $client,
        private readonly Config $config,
        private readonly RemoteAddress $remoteAddress,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function validate(string $token, string $formId): ValidationResult
    {
        $type = $this->config->getTypeFor($formId);
        if ($type === null) {
            return new ValidationResult(true);
        }

        $token = trim($token);
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
}
