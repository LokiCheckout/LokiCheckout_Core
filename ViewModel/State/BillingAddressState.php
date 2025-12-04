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

    public function load(string $attributeCode): mixed
    {
        return $this->getAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string $value): void
    {
        $billingAddress = $this->getAddress();
        $billingAddress->setData($attributeCode, $value);

        if ($this->sameAddressService->isEnabled()) {
            $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
            $shippingAddress->setData($attributeCode, $value);
        }

        $this->checkoutState->saveQuote($this->checkoutState->getQuote());
    }

    public function getAddress(): Address
    {
        $quote = $this->checkoutState->getQuote();
        $billingAddress = $quote->getBillingAddress();

        if (!$billingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $billingAddress->setCountryId($defaultCountryId);
            $this->checkoutState->saveQuote($quote);
        }

        $billingAddress->setIsShipping(false);
        $billingAddress->setIsBilling(true);

        return $billingAddress;
    }
}
