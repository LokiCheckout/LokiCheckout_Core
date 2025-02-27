<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\ShippingAsBilling;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

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
