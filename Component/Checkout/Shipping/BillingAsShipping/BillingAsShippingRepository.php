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
        $useSameAddress = $sameAddressService->isSame();

        if (true === $useSameAddress) {
            $sameAddressService->copyBillingToShipping();
        }

        return $useSameAddress;
    }

    public function saveValue(mixed $value): void
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $quote->getShippingAddress()->setSameAsBilling((int)$value);

        if ((bool)$value) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyBillingToShipping();
        }
    }
}
