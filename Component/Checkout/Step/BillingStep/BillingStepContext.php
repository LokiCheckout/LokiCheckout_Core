<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\BillingStep;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class BillingStepContext extends GenericContext
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
