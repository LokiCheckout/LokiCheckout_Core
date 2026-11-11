<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\ShippingAsBilling;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;

/**
 * @method AddressFormContext getContext()
 */
class ShippingAsBillingViewModel extends CheckoutViewModel
{
    protected bool $skipValidation = true;

    public function getFieldLabel(): string
    {
        return 'Shipping as Billing';
    }

    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->getSameAddressService()->isEnabled()) {
            return false;
        }

        if ($this->getContext()->getCheckoutState()->getQuote()->isVirtual()) {
            return false;
        }

        return parent::isAllowRendering();
    }
}
