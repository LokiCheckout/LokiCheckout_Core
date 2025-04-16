<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\ToggleSelect;

use Yireo\LokiCheckout\Component\Checkout\Address\AddressFormContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class ToggleSelectRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $sessionKey = $this->getContext()->getNewAddressSessionKey($this->getComponent());

        $value = (bool)$this->getContext()->getCheckoutSession()->getData($sessionKey);
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
        $this->getContext()->getCheckoutSession()->setData($sessionKey, (int)$value);
    }

    private function resetCustomerAddressId(): void
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $address->setCustomerAddressId(null);
        $this->getContext()->getCartRepository()->save($quote);
    }
}
