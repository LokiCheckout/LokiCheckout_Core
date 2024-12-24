<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\BillingStep;

use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class BillingStepContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CheckoutState $checkoutState,
        public readonly ValidatorInterface $stepValidator,
        public readonly BillingAddressState $billingAddressState,
        public readonly StepNavigator $stepNavigator
    ) {
    }
}
