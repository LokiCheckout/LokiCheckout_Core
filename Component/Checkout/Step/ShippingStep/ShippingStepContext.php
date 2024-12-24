<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\ShippingStep;

use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ShippingStepContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CheckoutState $checkoutState,
        public readonly ValidatorInterface $stepValidator,
        public readonly StepNavigator $stepNavigator
    ) {
    }
}
