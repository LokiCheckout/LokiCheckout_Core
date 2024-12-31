<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAsShipping;

use Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress\BillingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method BillingAddressContext getContext()
 */
class BillingAsShippingRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        $billingAsShipping = $this->getContext()->getBillingAsShippingService()->getValueFromAddress(
            $this->getContext()->getBillingAddressState()->getBillingAddress()
        );

        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
            $this->getContext()->getCartRepository()->save($this->getContext()->getQuote());
        }

        return $billingAsShipping;
    }

    protected function saveData(mixed $data): void
    {
        $billingAsShipping = (bool)$data;
        $billingAddress = $this->getContext()->getBillingAddressState()->getBillingAddress();

        $billingAddress->setSameAsBilling((int)$billingAsShipping);
        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
        }

        $this->getContext()->getCartRepository()->save($this->getContext()->getQuote());
    }

    protected function copyShippingToBilling()
    {
        $this->getContext()->getBillingAsShippingService()->copy(
            $this->getContext()->getShippingAddressState()->getShippingAddress(),
            $this->getContext()->getBillingAddressState()->getBillingAddress(),
        );
    }
}
