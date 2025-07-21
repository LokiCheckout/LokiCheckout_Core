<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\State;

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
    ) {
    }

    public function load(string $attributeCode): mixed
    {
        return $this->getShippingAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string|bool|int $value): void
    {
        $quote = $this->checkoutState->getQuote();

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setData($attributeCode, $value);

        if ($this->sameAddressService->isEnabled()) {
            $quote->getBillingAddress()->setData($attributeCode, $value);
        }

        $this->checkoutState->saveQuote($quote);
    }
    public function getShippingAddress(): Address
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $shippingAddress->setCountryId($defaultCountryId);
            $this->checkoutState->saveQuote($quote);
        }

        return $shippingAddress;
    }
}
