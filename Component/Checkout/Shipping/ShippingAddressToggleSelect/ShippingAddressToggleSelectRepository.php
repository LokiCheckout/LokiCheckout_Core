<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressToggleSelect;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressToggleSelectRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        return (bool)$this->getCheckoutSession()->getIsNewShippingAddress();
    }

    protected function saveData(mixed $data): void
    {
        $this->getCheckoutSession()->setIsNewShippingAddress((bool)$data);
    }

    protected function getCheckoutSession(): CheckoutSession
    {
        return $this->getContext()->getCheckoutSession();
    }
}
