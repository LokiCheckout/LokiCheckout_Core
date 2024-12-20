<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step;

use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingStep extends Generic implements StepInterface
{
    public function __construct(
        private readonly ValidatorInterface $stepValidator,
        private readonly BillingAddressState $billingAddressState,
        private readonly StepNavigator $stepNavigator
    ) {
    }

    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): string
    {
        $quote = $this->billingAddressState->getBillingAddress()->getQuote();
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
        if ($this->stepNavigator->isCurrentStep($this)) {
            return true;
        }

        return false;
    }

    public function isVisible(): bool
    {
        return $this->stepNavigator->isCurrentStep($this);
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
