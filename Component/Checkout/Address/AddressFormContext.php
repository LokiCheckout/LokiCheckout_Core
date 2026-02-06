<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address;

use Loki\Components\Component\AbstractComponentContext;
use Magento\Quote\Api\Data\AddressInterface;
use Loki\Components\Component\ComponentInterface;

/**
 * @mixin AddressContext
 */
class AddressFormContext extends AbstractComponentContext
{
    public function getNewAddressSessionKey(ComponentInterface $component): string
    {
        if ($this->isBillingAddressComponent($component)) {
            return 'loki-checkout.billing.address.is_new';
        }

        return 'loki-checkout.shipping.address.is_new';
    }

    public function isBillingAddressComponent(ComponentInterface $component): bool
    {
        return (bool)stristr($component->getName(), '.billing.');
    }

    public function isShippingAddressComponent(ComponentInterface $component): bool
    {
        return (bool)stristr($component->getName(), '.shipping.');
    }

    public function getCurrentAddress(ComponentInterface $component): AddressInterface
    {
        $quote = $this->getCheckoutState()->getQuote();
        if ($this->isBillingAddressComponent($component)) {
            return $quote->getBillingAddress();
        }

        return $quote->getShippingAddress();
    }

    public function isNewAddress(ComponentInterface $component): bool
    {
        if (false === $this->getCustomerSession()->isLoggedIn()) {
            return true;
        }

        $addresses = $this->getCustomerSession()->getCustomer()->getAddresses();
        if (empty($addresses)) {
            return true;
        }

        $sessionKey = $this->getNewAddressSessionKey($component);
        return (bool)$this->getCheckoutState()->getCheckoutSession()->getData($sessionKey);
    }
}
