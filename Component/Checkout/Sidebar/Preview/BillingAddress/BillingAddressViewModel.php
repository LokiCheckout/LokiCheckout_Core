<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Preview\BillingAddress;

use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Util\AddressRenderer;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;

class BillingAddressViewModel implements ArgumentInterface
{
    protected bool $skipValidation = true;
    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly AddressRenderer $addressRenderer,
        private readonly SameAddressService $sameAddressService
    ) {
    }

    public function isSameAsShipping(): bool
    {
        return $this->sameAddressService->isSame();
    }

    public function getHtml(): string
    {
        if ($this->isSameAsShipping()) {
            return '';
        }

        $address = $this->billingAddressState->getAddress();
        return $this->addressRenderer->getHtml($address);
    }
}
