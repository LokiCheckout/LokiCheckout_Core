<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ShippingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function load(string $attributeCode): mixed
    {
        return $this->getShippingAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string $value): void
    {
        $address = $this->getShippingAddress();
        $address->setData($attributeCode, $value);
        $address->getResource()->save($address);
    }
    public function getShippingAddress(): Address
    {
        $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $shippingAddress->setCountryId($defaultCountryId);
        }

        return $shippingAddress;
    }
}
