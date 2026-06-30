<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class BillingAddressTitle implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function getTitle(AbstractBlock $block): string
    {
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

        return 'Billing Address';
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
