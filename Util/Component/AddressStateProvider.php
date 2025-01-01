<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

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

        if (strstr($block, 'billing.address')) {
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
