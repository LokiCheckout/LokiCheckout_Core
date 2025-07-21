<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Payment\ApplePay;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Payment\ApplePayRequest;

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
