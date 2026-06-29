<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\State;

use LokiCheckout\Core\Shipment\ShippingAddressResolverListing;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address;
use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\CheckoutState;

class ShippingAddressState implements AddressStateInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SameAddressService $sameAddressService,
        private readonly ShippingAddressResolverListing $addressResolverListing,
    ) {
    }

    /**
     * @deprecated Use get() instead
     * @param string $attributeCode
     *
     * @return mixed
     */
    public function load(string $attributeCode): mixed
    {
        return $this->get($attributeCode);
    }

    public function get(string $attributeCode): mixed
    {
        return $this->getAddress()->getData($attributeCode);
    }

    /**
     * @deprecated Use set() instead
     * @param string $attributeCode
     * @param string $value
     *
     * @return void
     */
    public function save(string $attributeCode, string $value): void
    {
        $this->set($attributeCode, $value);
    }

    public function set(string $attributeCode, string $value): void
    {
        $this->getAddress()->setData($attributeCode, $value);

        if ($this->sameAddressService->isSame()) {
            $this->checkoutState->getQuote()->getBillingAddress()->setData($attributeCode, $value);
        }
    }

    public function getAddress(): Address
    {
        $shippingAddress = $this->getShippingAddress();

        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $shippingAddress->setCountryId($defaultCountryId);
        }

        if (!$this->checkoutState->getCustomerSession()->isLoggedIn()) {
            $shippingAddress->setCustomerAddressId(null);
        }

        $shippingAddress->setIsShipping(true);
        $shippingAddress->setIsBilling(false);

        return $shippingAddress;
    }

    private function getShippingAddress(): Address
    {
        foreach ($this->addressResolverListing->getResolvers() as $resolver) {
            $address = $resolver->resolve();
            if ($address instanceof Address) {
                $address->setSameAsBilling(0);
                return $address;
            }
        }

        return $this->checkoutState->getQuote()->getShippingAddress();
    }
}
