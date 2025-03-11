<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\ShippingAsBilling;

use Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressFormContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
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
        $billingAsShipping = (bool)$value;
        $address = $this->getContext()->getShippingAddressState()->getShippingAddress();
        $address->setSameAsBilling((int)$billingAsShipping);
        $this->getContext()->getCartRepository()->save($this->getContext()->getCheckoutState()->getQuote());

        if (true === $billingAsShipping) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyShippingToBilling();
        }
    }
}
