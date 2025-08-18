<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step\BillingStep;

use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;
use Loki\Components\Component\ComponentViewModel;

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
        $quote = $this->getContext()->getCheckoutState()->getQuote();
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
        return $this->getContext()->getStepNavigator()->isCurrentStep($this);
    }

    public function isVisible(): bool
    {
        return $this->getContext()->getStepNavigator()->isCurrentStep($this);
    }

    public function validate(): bool|array
    {
        return $this->getContext()->getStepValidator()->validate();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStep';
    }

    public function getJsData(): array
    {
        return [
            'visible' => $this->isVisible(),
            'step' => $this->getStep(),
        ];
    }

    public function getStep(): string
    {
        return 'none';
    }
}
