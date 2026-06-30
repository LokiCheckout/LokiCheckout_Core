<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Payment\ApplePay;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Payment\ApplePayRequest;

abstract class ApplePayViewModel extends CheckoutViewModel
{
    #[JsProperty('applePayRequest')]
    abstract public function getApplePayRequest(): ApplePayRequest;

    #[JsProperty('applePayVersion')]
    public function getApplePayVersion(): int
    {
        return 10;
    }
}
