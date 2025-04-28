<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\ShippingAsBilling;

use Yireo\LokiCheckout\Component\Checkout\Address\AddressFormContext;
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
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $address = $quote->getShippingAddress();
        $address->setSameAsBilling((int)$billingAsShipping);
        $this->getContext()->getCheckoutState()->saveQuote($quote);

        if ($billingAsShipping) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyShippingToBilling();
        }
    }
}
