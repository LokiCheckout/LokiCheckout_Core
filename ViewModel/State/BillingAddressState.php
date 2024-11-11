<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;

class BillingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession,
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    public function load(string $attributeCode): mixed
    {
        return $this->getBillingAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string $value)
    {
        $address = $this->getBillingAddress();
        $address->setData($attributeCode, $value);
        $address->getResource()->save($address);
        //$this->checkoutSession->getQuote()->save();
    }

    public function getQuote(): Quote
    {
        return $this->checkoutSession->getQuote();
    }

    public function getBillingAddress(): Address
    {
        $billingAddress = $this->getQuote()->getBillingAddress();
        if (!$billingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $billingAddress->setCountryId($defaultCountryId);
        }

        return $billingAddress;
    }
}
