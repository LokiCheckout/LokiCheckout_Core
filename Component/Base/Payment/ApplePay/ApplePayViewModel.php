<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Payment\ApplePay;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiCheckout\Payment\ApplePayRequest;

abstract class ApplePayViewModel extends CheckoutViewModel
{
    public function getJsData(): array
    {
        return [
            ...parent::getJsData(),
            'applePayVersion' => $this->getApplePayVersion(),
            'applePayRequest' => $this->getApplePayRequest(),
        ];
    }

    abstract public function getApplePayRequest(): ApplePayRequest;

    public function getApplePayVersion(): int
    {
        return 10;
    }
}
