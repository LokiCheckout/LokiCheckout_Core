<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Payment\ApplePay;

use Yireo\LokiComponents\Component\ComponentRepository;

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
            $this->onValidateMerchant(
                $value['validationUrl'],
                $value['originDomain'],
            );
        }
    }

    // @todo \MultiSafepay\ConnectCore\Model\Ui\Gateway\ApplePayConfigProvider

    abstract protected function onValidateMerchant(string $validationUrl, string $originDomain): void;
}
