<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\ShippingAsBilling;

use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;
use Loki\Components\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class ShippingAsBillingRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $sameAddressService = $this->getContext()->getSameAddressService();
        $useSameAddress = $sameAddressService->isSame();

        if (true === $useSameAddress) {
            $sameAddressService->copyShippingToBilling();
        }

        return $useSameAddress;
    }

    public function saveValue(mixed $value): void
    {
        $billingAsShipping = (bool)$value;
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $address = $quote->getShippingAddress();
        $address->setSameAsBilling((int)$billingAsShipping);

        if ($billingAsShipping) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyShippingToBilling();
        }
    }
}
