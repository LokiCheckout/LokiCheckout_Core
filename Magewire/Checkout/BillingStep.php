<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Magento\Framework\Phrase;
use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class BillingStep extends AbstractComponent implements StepInterface
{
    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): Phrase
    {
        return __('Billing');
    }

    public function isEnabled(): bool
    {
        foreach ($this->checkoutState->getQuote()->getAllItems() as $quoteItem) {
            if ($quoteItem->getBasePrice() > 0) {
                return true;
            }
        }

        return false;
    }

    public function isAccessable(): bool
    {
        // @todo: Check if billing step could be access already
        return true;
    }

    public function isFirst(): bool
    {
        return $this->checkoutState->getStepNavigator()->isFirstStep($this);
    }

    public function isLast(): bool
    {
        return $this->checkoutState->getStepNavigator()->isLastStep($this);
    }
}
