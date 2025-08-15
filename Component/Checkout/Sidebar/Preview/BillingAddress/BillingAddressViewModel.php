<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Preview\BillingAddress;

use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Util\AddressRenderer;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;

class BillingAddressViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly AddressRenderer $addressRenderer
    ) {
    }

    public function isSameAsShipping(): bool
    {
        return $this->billingAddressState->getBillingAddress()->getSameAsBilling() || $this->shippingAddressState->getShippingAddress()->getSameAsBilling();
    }

    public function getHtml(): string
    {
        if ($this->isSameAsShipping()) {
            return '';
        }

        $address = $this->billingAddressState->getBillingAddress();
        return $this->addressRenderer->getHtml($address);
    }
}
