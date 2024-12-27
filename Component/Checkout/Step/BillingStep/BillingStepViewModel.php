<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\BillingStep;

use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method BillingStepContext getContext()
 */
class BillingStepViewModel extends ComponentViewModel implements StepViewModelInterface
{
    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): string
    {
        $quote = $this->getContext()->billingAddressState->getBillingAddress()->getQuote();
        if (false === $quote->getGrandTotal() > 0) {
            return 'Review'; // @todo: Test for this
        }

        return 'Review & Payments';
    }

    public function isEnabled(): bool
    {
        // @todo: Return false if only free products

        return true;
    }

    public function isAccessable(): bool
    {
        // @todo: Move this logic to step navigator itself
        if ($this->getContext()->stepNavigator->isCurrentStep($this)) {
            return true;
        }

        return false;
    }

    public function isVisible(): bool
    {
        return $this->getContext()->stepNavigator->isCurrentStep($this);
    }

    public function validate(): true|array
    {
        return $this->getContext()->stepValidator->validate();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStep';
    }

    public function getJsData(): ?array
    {
        return [
            'visible' => $this->isVisible(),
        ];
    }
}
