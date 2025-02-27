<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class StepForwardButtonLabel implements ArgumentInterface
{
    public function __construct(
        private CheckoutState $checkoutState
    ) {
    }

    public function getLabel(AbstractBlock $block): string
    {
        $buttonLabelWithoutPayment = trim((string)$block->getButtonLabelWithoutPayment());
        $quote = $this->checkoutState->getQuote();

        if ($buttonLabelWithoutPayment && false === $quote->getGrandTotal() > 0) {
            return $buttonLabelWithoutPayment;
        }

        return $block->getButtonLabel();
    }
}
