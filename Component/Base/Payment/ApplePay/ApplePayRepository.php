<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Payment\ApplePay;

use Loki\Components\Component\ComponentRepository;

abstract class ApplePayRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        return null;
    }

    public function saveValue(mixed $value): void
    {
        if (false === is_array($value)) {
            return;
        }

        if (isset($value['task']) && $value['task'] === 'onValidateMerchant') {
            $validationUrl = $value['validationUrl'] ?? null;
            $originDomain = $value['originDomain'] ?? null;
            if (false === is_string($validationUrl) || false === is_string($originDomain)) {
                return;
            }

            // The validation URL is supplied by the browser, so prevent it from being used for SSRF
            if (false === $this->isValidValidationUrl($validationUrl)) {
                return;
            }

            $this->onValidateMerchant($validationUrl, $originDomain);
        }
    }

    // @todo \MultiSafepay\ConnectCore\Model\Ui\Gateway\ApplePayConfigProvider

    abstract protected function onValidateMerchant(string $validationUrl, string $originDomain): void;

    /**
     * @see https://developer.apple.com/documentation/apple_pay_on_the_web/setting_up_your_server
     */
    protected function isValidValidationUrl(string $validationUrl): bool
    {
        $parts = parse_url($validationUrl);
        if (false === is_array($parts)) {
            return false;
        }

        if (($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }

        if (isset($parts['port']) && (int)$parts['port'] !== 443) {
            return false;
        }

        $host = strtolower((string)($parts['host'] ?? ''));

        return (bool)preg_match('/^(cn-)?apple-pay-gateway(-[a-z0-9-]+)?\.apple\.com$/', $host);
    }
}
