<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Shipping\BillingAsShipping;

use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;
use Loki\Components\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class BillingAsShippingRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $sameAddressService = $this->getContext()->getSameAddressService();
        $useSameAddress = $sameAddressService->isEnabled();

        if (true === $useSameAddress) {
            $sameAddressService->copyBillingToShipping();
        }

        return $useSameAddress;
    }

    public function saveValue(mixed $value): void
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $billingAsShipping = (bool)$value;
        $address = $quote->getShippingAddress();
        $address->setSameAsBilling((int)$billingAsShipping);
        $this->getContext()->getCheckoutState()->saveQuote($quote);

        if ($billingAsShipping) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyBillingToShipping();
        }
    }
}
