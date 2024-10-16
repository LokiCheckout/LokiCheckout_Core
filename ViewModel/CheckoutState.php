<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote;

/**
 * @deprecated: Use smaller state classes instead
 */
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

    // @todo: Where is this needed?
    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    /**
     * @deprecated: Use \Yireo\LokiCheckout\ViewModel\State\ShippingAddressState instead
     */
    public function getShippingAddressValue(string $attributeCode)
    {
        return $this->getQuote()->getShippingAddress()->getData($attributeCode);
    }

    /**
 * @deprecated: Use \Yireo\LokiCheckout\ViewModel\State\BillingAddressState instead
     */
    public function getBillingAddressValue(string $attributeCode)
    {
        return $this->getQuote()->getBillingAddress()->getData($attributeCode);
    }


/**
 * @deprecated: Use \Yireo\LokiCheckout\ViewModel\State\ShippingAddressState instead
 */
    public function setShippingAddressValue(string $attributeCode, mixed $value): void
    {
        $this->getQuote()->getShippingAddress()->setData($attributeCode, $value);
        $this->getQuote()->save();
    }

    /**
     * @deprecated: Use \Yireo\LokiCheckout\ViewModel\State\BillingAddressState instead
     */
    public function setBillingAddressValue(string $attributeCode, mixed $value): void
    {
        $this->getQuote()->getBillingAddress()->setData($attributeCode, $value);
        $this->getQuote()->save();
    }
}
