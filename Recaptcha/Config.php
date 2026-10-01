<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Reads the Magento core reCAPTCHA configuration (Stores > Configuration > Security >
 * Google reCAPTCHA Storefront). The config paths and scopes mirror the providers of
 * Magento_ReCaptchaVersion2Checkbox, Magento_ReCaptchaVersion2Invisible and
 * Magento_ReCaptchaVersion3Invisible, so that no dependency on those modules is needed.
 *
 * Note that `public_key` and `private_key` use the Encrypted backend model, which means
 * ScopeConfigInterface already returns them decrypted. They must never be decrypted again.
 */
class Config implements ArgumentInterface
{
    private const XML_PATH_TYPE_FOR = 'recaptcha_frontend/type_for/%s';
    private const XML_PATH_TYPE_SETTING = 'recaptcha_frontend/%s/%s';
    private const XML_PATH_VALIDATION_FAILURE = 'recaptcha_frontend/failure_messages/validation_failure_message';
    private const XML_PATH_TECHNICAL_FAILURE = 'recaptcha_frontend/failure_messages/technical_failure_message';

    private const DEFAULT_SCORE_THRESHOLD = 0.5;

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getTypeFor(string $formId): ?string
    {
        $type = (string)$this->scopeConfig->getValue(
            sprintf(self::XML_PATH_TYPE_FOR, $formId),
            ScopeInterface::SCOPE_STORE
        );

        return Type::isValid($type) ? $type : null;
    }

    public function isEnabledFor(string $formId): bool
    {
        $type = $this->getTypeFor($formId);
        if ($type === null) {
            return false;
        }

        return $this->getSiteKey($type) !== '' && $this->getSecretKey($type) !== '';
    }

    /**
     * All reCAPTCHA types that are actively used by any of the checkout forms.
     *
     * @return string[]
     */
    public function getActiveTypes(): array
    {
        $types = [];
        foreach (FormId::getAll() as $formId) {
            if (false === $this->isEnabledFor($formId)) {
                continue;
            }

            $types[] = $this->getTypeFor($formId);
        }

        return array_values(array_unique($types));
    }

    public function getSiteKey(string $type): string
    {
        return trim((string)$this->getTypeSetting($type, 'public_key', ScopeInterface::SCOPE_WEBSITE), " \f\n\r\t\v\x00");
    }

    public function getSecretKey(string $type): string
    {
        return trim((string)$this->getTypeSetting($type, 'private_key', ScopeInterface::SCOPE_WEBSITE), " \f\n\r\t\v\x00");
    }

    /**
     * Settings passed to `grecaptcha.render()`, matching the UiConfigProviders of Magento.
     *
     * @return array<string, string>
     */
    public function getRenderingSettings(string $type): array
    {
        $settings = [
            'sitekey' => $this->getSiteKey($type),
            'theme' => (string)$this->getTypeSetting($type, 'theme', ScopeInterface::SCOPE_WEBSITE),
            'hl' => (string)$this->getTypeSetting($type, 'lang', ScopeInterface::SCOPE_STORE),
        ];

        if (Type::isInvisible($type)) {
            $settings['size'] = 'invisible';
            $settings['badge'] = (string)$this->getTypeSetting($type, 'position', ScopeInterface::SCOPE_WEBSITE);

            return $settings;
        }

        $settings['size'] = (string)$this->getTypeSetting($type, 'size', ScopeInterface::SCOPE_WEBSITE);

        return $settings;
    }

    public function getScoreThreshold(): float
    {
        $scoreThreshold = $this->getTypeSetting(Type::V3, 'score_threshold', ScopeInterface::SCOPE_WEBSITE);
        if (false === is_numeric($scoreThreshold)) {
            return self::DEFAULT_SCORE_THRESHOLD;
        }

        return min(1.0, max(0.1, (float)$scoreThreshold));
    }

    public function getValidationFailureMessage(): string
    {
        $message = trim((string)$this->scopeConfig->getValue(
            self::XML_PATH_VALIDATION_FAILURE,
            ScopeInterface::SCOPE_STORE
        ), " \f\n\r\t\v\x00");

        if ($message !== '') {
            return $message;
        }

        return (string)__('reCAPTCHA verification failed.');
    }

    public function getTechnicalFailureMessage(): string
    {
        $message = trim((string)$this->scopeConfig->getValue(
            self::XML_PATH_TECHNICAL_FAILURE,
            ScopeInterface::SCOPE_STORE
        ), " \f\n\r\t\v\x00");

        if ($message !== '') {
            return $message;
        }

        return (string)__('Something went wrong with reCAPTCHA. Please contact the store owner.');
    }

    private function getTypeSetting(string $type, string $field, string $scope): mixed
    {
        $configGroup = Type::getConfigGroup($type);
        if ($configGroup === null) {
            return null;
        }

        return $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_TYPE_SETTING, $configGroup, $field),
            $scope
        );
    }
}
