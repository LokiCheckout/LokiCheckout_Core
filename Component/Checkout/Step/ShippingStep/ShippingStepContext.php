<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\ShippingStep;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ShippingStepContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private GenericContext $parentContext,
        private readonly ValidatorInterface $stepValidator,
    ) {
    }

    public function getStepValidator(): ValidatorInterface
    {
        return $this->stepValidator;
    }
}
