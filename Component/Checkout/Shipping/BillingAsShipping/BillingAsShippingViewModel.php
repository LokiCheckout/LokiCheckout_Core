<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Shipping\BillingAsShipping;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;

/**
 * @method AddressFormContext getContext()
 */
class BillingAsShippingViewModel extends CheckoutViewModel
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

        return parent::isAllowRendering();
    }
}
