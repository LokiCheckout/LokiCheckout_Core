<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address;
use Yireo\LokiCheckout\Util\SameAddressService;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class BillingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private ScopeConfigInterface $scopeConfig,
        private SameAddressService $sameAddressService,
    ) {
    }

    public function load(string $attributeCode): mixed
    {
        return $this->getBillingAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string $value): void
    {
        $billingAddress = $this->getBillingAddress();
        $billingAddress->setData($attributeCode, $value);

        if ($this->sameAddressService->isEnabled()) {
            $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
            $shippingAddress->setData($attributeCode, $value);
        }

        $this->checkoutState->saveQuote($this->checkoutState->getQuote());
    }

    public function getBillingAddress(): Address
    {
        $quote = $this->checkoutState->getQuote();
        $billingAddress = $quote->getBillingAddress();

        if (!$billingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $billingAddress->setCountryId($defaultCountryId);
            $this->checkoutState->saveQuote($quote);
        }

        return $billingAddress;
    }
}
