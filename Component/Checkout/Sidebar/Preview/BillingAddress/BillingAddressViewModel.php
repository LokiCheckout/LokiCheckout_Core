<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Preview\BillingAddress;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddressViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly AddressRenderer $addressRenderer
    ) {
    }

    public function isSameAsShipping(): bool
    {
        return (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
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
