<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class BillingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    public function load(string $attributeCode): mixed
    {
        return $this->getBillingAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string $value): void
    {
        $address = $this->getBillingAddress();
        $address->setData($attributeCode, $value);
        $address->getResource()->save($address);
    }
    public function getBillingAddress(): Address
    {
        $billingAddress = $this->checkoutState->getQuote()->getBillingAddress();
        if (!$billingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $billingAddress->setCountryId($defaultCountryId);
        }

        return $billingAddress;
    }
}
