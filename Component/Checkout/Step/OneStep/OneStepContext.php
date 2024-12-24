<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\OneStep;

use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class OneStepContext implements ComponentContextInterface
{
    public function __construct(
        public readonly ValidatorInterface $stepValidator,
        public readonly StepNavigator $stepNavigator
    ) {
    }
}
