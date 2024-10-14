<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\ViewModel\State;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Address;

class BillingAddressState implements ArgumentInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession
    ) {
    }

    public function load(string $attributeCode)
    {
        return $this->getBillingAddress()->getData($attributeCode);
    }

    public function save(string $attributeCode, string $value)
    {
        $this->getBillingAddress()->setData($attributeCode, $value);
        $this->checkoutSession->getQuote()->save();
    }

    public function getBillingAddress(): Address
    {
        return $this->checkoutSession->getQuote()->getBillingAddress();
    }
}
