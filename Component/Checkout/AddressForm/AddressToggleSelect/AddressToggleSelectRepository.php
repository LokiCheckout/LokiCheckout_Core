<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressToggleSelect;

use Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressFormContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFormContext getContext()
 */
class AddressToggleSelectRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $sessionKey = $this->getContext()->getNewAddressSessionKey($this->getComponent());

        return (bool)$this->getContext()->getCheckoutSession()->getData($sessionKey);
    }

    public function saveValue(mixed $value): void
    {

        if ((int)$value > 0) {
            $this->resetCustomerAddressId();
        }

        $sessionKey = $this->getContext()->getNewAddressSessionKey($this->getComponent());
        $this->getContext()->getCheckoutSession()->setData($sessionKey, (int)$value);
    }

    // @todo: Also do this on load?
    private function resetCustomerAddressId(): void
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        $address->setCustomerAddressId(null);
        $this->getContext()->getCartRepository()->save($quote);
    }
}
