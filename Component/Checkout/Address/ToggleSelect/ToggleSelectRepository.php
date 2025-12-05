<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\ToggleSelect;

use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;
use Loki\Components\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class ToggleSelectRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $value = (bool)$this->getContext()->isNewAddress($this->getComponent());
        if ((int)$value > 0) {
            $this->resetCustomerAddressId();
        }

        return $value;
    }

    public function saveValue(mixed $value): void
    {
        if ((int)$value > 0) {
            $this->resetCustomerAddressId();
        }

        $sessionKey = $this->getContext()->getNewAddressSessionKey($this->getComponent());
        $this->getContext()->getCheckoutState()->getCheckoutSession()->setData($sessionKey, (int)$value);
    }

    private function resetCustomerAddressId(): void
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $address->setCustomerAddressId(null);

        if ($quote->isVirtual()) {
            $quote->getShippingAddress()->setCustomerAddressId(null);
        }
    }
}
