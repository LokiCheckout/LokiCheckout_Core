<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\OneStep;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class OneStepContext extends GenericContext
{
    public function __construct(
        public readonly ValidatorInterface $stepValidator, // @todo: This does not call upon parent constructor
    ) {
    }

    public function getStepValidator(): ValidatorInterface
    {
        return $this->stepValidator;
    }
}
