<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\State;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address;
use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\CheckoutState;

class BillingAddressState implements AddressStateInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SameAddressService $sameAddressService,
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

        if ($this->sameAddressService->isEnabled()) {
            $this->checkoutState->getQuote()->getShippingAddress()->setData($attributeCode, $value);
        }
    }

    public function getAddress(): Address
    {
        $quote = $this->checkoutState->getQuote();
        $billingAddress = $quote->getBillingAddress();

        if (!$billingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $billingAddress->setCountryId($defaultCountryId);
        }

        if (!$this->checkoutState->getCustomerSession()->isLoggedIn()) {
            $billingAddress->setCustomerAddressId(null);
        }

        $billingAddress->setIsShipping(false);
        $billingAddress->setIsBilling(true);

        return $billingAddress;
    }
}
