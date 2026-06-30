<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use LokiCheckout\Core\Shipment\ShippingAddressResolverListing;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ShippingAddressTitle implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ShippingAddressResolverListing $shippingAddressResolverListing,
    ) {
    }

    public function getTitle(AbstractBlock $block): string
    {
        if ($this->shippingAddressResolverListing->resolves()) {
            return $this->getPickupAddressTitle($block);
        }

        if ($this->checkoutState->getCustomerSession()->isLoggedIn()) {
            return $this->getCustomerNewAddressTitle($block);
        }

        return $this->getDefaultTitle($block);
    }

    private function getDefaultTitle(AbstractBlock $block): string
    {
        $title = $block->getDefaultTitle();
        if (!empty($title)) {
            return $title;
        }

        return 'Enter your Shipping Address';
    }

    private function getPickupAddressTitle(AbstractBlock $block): string
    {
        $title = $block->getPickupAddressTitle();
        if (!empty($title)) {
            return $title;
        }

        return 'Pickup Address';
    }

    private function getCustomerNewAddressTitle(AbstractBlock $block): string
    {
        $title = $block->getCustomerNewAddressTitle();
        if (!empty($title)) {
            return $title;
        }

        return 'Enter a New Address';
    }

    private function getCustomerSelectTitle(AbstractBlock $block): string
    {
        $title = $block->getCustomerSelectTitle();
        if (!empty($title)) {
            return $title;
        }

        return 'Select your Address';
    }
}
