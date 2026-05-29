<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step\BillingStep;

use LokiCheckout\Core\Component\Checkout\Step\AbstractStepViewModel;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * @method BillingStepContext getContext()
 */
class BillingStepViewModel extends AbstractStepViewModel
{
    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): string
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if (false === $quote->getGrandTotal() > 0) {
            return 'Review'; // @todo: Test for this
        }

        $titleBlock = $this->getBlock()?->getLayout()->getBlock('loki-checkout.steps.billing.title');
        if ($titleBlock instanceof AbstractBlock) {
            $title = $titleBlock->getTitle();
            if (!empty($title)) {
                return $title;
            }
        }

        return 'Review & Payments';
    }

    public function isAccessable(): bool
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if ($quote->hasVirtualItems()) {
            return true;
        }

        return parent::isAccessable();
    }

    public function validate(): bool|array
    {
        $result = $this->getContext()->getStepValidator()->validate();
        $this->valid = true === $result;

        return $result;
    }
}
