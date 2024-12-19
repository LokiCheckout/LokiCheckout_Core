<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\SetterInjection;

use Magento\Checkout\Model\Session as CheckoutSession;

trait InjectCheckoutSession
{
    protected ?CheckoutSession $checkoutSession = null;

    public function setCheckoutSession(CheckoutSession $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }
}
