<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\BillingAsShipping;

use Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress\BillingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method BillingAddressContext getContext()
 */
class BillingAsShippingRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        $sameAddressService = $this->getContext()->getSameAddressService();
        $useSameAddress = $sameAddressService->isEnabled();

        if (true === $useSameAddress) {
            $sameAddressService->copyBillingToShipping();
        }

        return $useSameAddress;
    }

    protected function saveData(mixed $data): void
    {
        $billingAsShipping = (bool)$data;
        $address = $this->getContext()->getShippingAddressState()->getShippingAddress();
        $address->setSameAsBilling((int)$billingAsShipping);
        $this->getContext()->getCartRepository()->save($this->getContext()->getQuote());

        if (true === $billingAsShipping) {
            $sameAddressService = $this->getContext()->getSameAddressService();
            $sameAddressService->copyBillingToShipping();
        }
    }
}
