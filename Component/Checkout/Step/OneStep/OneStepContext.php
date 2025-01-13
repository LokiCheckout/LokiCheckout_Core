<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\OneStep;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class OneStepContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly CheckoutContext $parentContext,
        private readonly ValidatorInterface $stepValidator,
    ) {
    }
    public function getStepValidator(): ValidatorInterface
    {
        return $this->stepValidator;
    }
}
