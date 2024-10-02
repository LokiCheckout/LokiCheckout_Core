<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Magento\Framework\Phrase;
use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class ShippingStep extends AbstractComponent implements StepInterface
{
    public function getCode(): string
    {
        return 'shipping';
    }

    public function getLabel(): Phrase
    {
        return __('Shipping');
    }

    public function isEnabled(): bool
    {
        foreach ($this->checkoutState->getQuote()->getAllItems() as $quoteItem) {
            if (false === (bool)$quoteItem->getIsVirtual()) {
                return true;
            }
        }

        return false;
    }

    public function isAccessable(): bool
    {
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
