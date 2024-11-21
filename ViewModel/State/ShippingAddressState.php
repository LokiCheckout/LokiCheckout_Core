<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;

class ShippingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession,
        private ScopeConfigInterface $scopeConfig,
        private ResourceConnection $resourceConnection
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

    public function getQuote(): Quote
    {
        return $this->checkoutSession->getQuote();
    }

    public function getShippingAddress(): Address
    {
        $shippingAddress = $this->getQuote()->getShippingAddress();
        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $shippingAddress->setCountryId($defaultCountryId);
        }

        return $shippingAddress;
    }
}
