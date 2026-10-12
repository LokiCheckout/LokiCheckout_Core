<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use LokiCheckout\Core\ViewModel\State\AddressStateInterface;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;

class AddressStateProvider
{
    public function __construct(
        private readonly ShippingAddressState $shippingAddressState,
        private readonly BillingAddressState  $billingAddressState,
    ) {
    }

    public function getAddressType(AbstractBlock|string $block): string
    {
        if ($block instanceof AbstractBlock) {
            $block = $block->getNameInLayout();
        }

        if (str_contains($block, 'billing.address') || str_contains($block, 'billing_address')) {
            return 'billing';
        }

        return 'shipping';
    }

    public function getAddressState(AbstractBlock|string $block): AddressStateInterface
    {
        if ($this->isBillingAddressType($block)) {
            return $this->getBillingAddressState();
        }

        return $this->getShippingAddressState();
    }

    public function isShippingAddressType(AbstractBlock|string $block): bool
    {
        return $this->getAddressType($block) === 'shipping';
    }

    public function isBillingAddressType(AbstractBlock|string $block): bool
    {
        return $this->getAddressType($block) === 'billing';
    }

    public function getShippingAddressState(): ShippingAddressState
    {
        return $this->shippingAddressState;
    }

    public function getBillingAddressState(): BillingAddressState
    {
        return $this->billingAddressState;
    }
}
