<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\State;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\Quote\Address;

class BillingAddressState implements AddressStateInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession
    ) {
    }

    public function load(string $attributeCode): mixed
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
