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
        $billingAsShipping = $this->getContext()->billingAsShippingService->getValueFromAddress(
            $this->getContext()->billingAddressState->getBillingAddress()
        );

        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
            $this->getContext()->cartRepository->save($this->getContext()->billingAddressState->getQuote());
        }

        return $billingAsShipping;
    }

    protected function saveData(mixed $data): void
    {
        if (!isset($data['billingAsShipping'])) {
            return;
        }

        $billingAsShipping = $data['billingAsShipping'];
        $billingAddress = $this->getContext()->billingAddressState->getBillingAddress();

        $billingAddress->setSameAsBilling((int)$billingAsShipping);
        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
        }

        $this->getContext()->cartRepository->save($this->getContext()->billingAddressState->getQuote());
    }

    protected function copyShippingToBilling()
    {
        $this->getContext()->billingAsShippingService->copy(
            $this->getContext()->shippingAddressState->getShippingAddress(),
            $this->getContext()->billingAddressState->getBillingAddress(),
        );
    }
}
