<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\ShippingAsBilling;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

class ShippingAsBillingViewModel extends CheckoutViewModel
{
    public function getFieldLabel(): string
    {
        return 'Shipping as Billing';
    }

    public function isAllowRendering(): bool
    {
        if ($this->getContext()->getCheckoutState()->getQuote()->isVirtual()) {
            return false;
        }

        return parent::isAllowRendering();
    }
}
