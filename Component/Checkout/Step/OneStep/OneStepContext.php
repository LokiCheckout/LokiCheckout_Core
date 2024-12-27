<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\OneStep;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class OneStepContext extends GenericContext
{
    public function __construct(
        public readonly ValidatorInterface $stepValidator,
    ) {
    }

    public function getStepValidator(): ValidatorInterface
    {
        return $this->stepValidator;
    }
}
