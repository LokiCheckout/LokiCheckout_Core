<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\ShippingAsBilling;

use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAsBillingRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $sameAddressService = $this->getContext()->getSameAddressService();
        $useSameAddress = $sameAddressService->isEnabled();

        if (true === $useSameAddress) {
            $sameAddressService->copyShippingToBilling();
        }

        return $useSameAddress;
    }

    public function saveValue(mixed $value): void
    {
        $billingAsShipping = (bool)$data;
        $address = $this->getContext()->getShippingAddressState()->getShippingAddress();
        $address->setSameAsBilling((int)$billingAsShipping);
        $this->getContext()->getCartRepository()->save($this->getContext()->getQuote());

        if (true === $billingAsShipping) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyShippingToBilling();
        }
    }
}
