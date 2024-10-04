<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote;

class CheckoutState implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
    ) {
    }

    public function getQuote(): Quote
    {
        return $this->checkoutSession->getQuote();
    }

    public function getCheckoutSession(): CheckoutSession
    {
        return $this->checkoutSession;
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    public function getShippingAddressValue(string $attributeCode)
    {
        return $this->getQuote()->getShippingAddress()->getData($attributeCode);
    }

    public function getBillingAddressValue(string $attributeCode)
    {
        return $this->getQuote()->getBillingAddress()->getData($attributeCode);
    }

    public function setShippingAddressValue(string $attributeCode, mixed $value): void
    {
        $this->getQuote()->getShippingAddress()->setData($attributeCode, $value);
        $this->getQuote()->save();
    }

    public function setBillingAddressValue(string $attributeCode, mixed $value): void
    {
        $this->getQuote()->getBillingAddress()->setData($attributeCode, $value);
        $this->getQuote()->save();
    }
}
