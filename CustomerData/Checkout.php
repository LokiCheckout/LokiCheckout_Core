<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData;

use LokiCheckout\Core\CustomerData\Checkout\BillingAddressDataCollector;
use LokiCheckout\Core\CustomerData\Checkout\CartItemDataCollector;
use LokiCheckout\Core\CustomerData\Checkout\CustomerDataCollector;
use LokiCheckout\Core\CustomerData\Checkout\QuoteDataCollector;
use LokiCheckout\Core\CustomerData\Checkout\ShippingAddressDataCollector;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Customer\CustomerData\SectionSourceInterface;

class Checkout implements SectionSourceInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private QuoteDataCollector $quoteDataCollector,
        private CustomerDataCollector $customerDataCollector,
        private ShippingAddressDataCollector $shippingAddressDataCollector,
        private BillingAddressDataCollector $billingAddressDataCollector,
        private CartItemDataCollector $cartItemDataCollector
    ) {
    }

    public function getSectionData()
    {
        if (false === $this->checkoutState->hasQuote()) {
            return [];
        }

        return [
            ...$this->getQuoteSectionData(),
            'customer'         => $this->getCustomerSectionData(),
            'cart_items'       => $this->getCartItemsSectionData(),
            'shipping_address' => $this->getShippingAddressSectionData(),
            'billing_address'  => $this->getBillingAddressSectionData(),
        ];
    }

    private function getCustomerSectionData(): array
    {
        $customerId = (int)$this->checkoutState->getCustomerSession()->getCustomerId();
        return $this->customerDataCollector->getData($customerId);
    }

    private function getQuoteSectionData(): array
    {
        return $this->quoteDataCollector->getData($this->checkoutState->getQuote());
    }

    private function getCartItemsSectionData(): array
    {
        $items = [];
        foreach ($this->checkoutState->getQuote()->getAllItems() as $item) {
            $items[] = $this->cartItemDataCollector->getData($item);
        }

        return $items;
    }

    private function getShippingAddressSectionData(): array
    {
        $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
        return $this->shippingAddressDataCollector->getData($shippingAddress);
    }

    private function getBillingAddressSectionData(): array
    {
        $billingAddress = $this->checkoutState->getQuote()->getBillingAddress();
        return $this->billingAddressDataCollector->getData($billingAddress);
    }
}
