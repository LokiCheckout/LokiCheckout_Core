<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressToggleSelect;

use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressToggleSelectRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        return (bool)$this->getContext()->getCheckoutSession()->getIsNewShippingAddress();
    }

    public function saveValue(mixed $data): void
    {
        $this->getContext()->getCheckoutSession()->setIsNewShippingAddress((int)$data);
    }
}
