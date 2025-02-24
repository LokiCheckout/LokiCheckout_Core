<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Address;
use Yireo\LokiCheckout\Util\SameAddressService;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ShippingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private ScopeConfigInterface $scopeConfig,
        private SameAddressService $sameAddressService,
        private CartRepositoryInterface $cartRepository
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

        $this->cartRepository->save($quote);
    }
    public function getShippingAddress(): Address
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->scopeConfig->getValue('general/country/default');
            $shippingAddress->setCountryId($defaultCountryId);
            $this->cartRepository->save($quote);
        }

        return $shippingAddress;
    }
}
