<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepComponentInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class OneStep extends GenericComponent implements StepComponentInterface
{
    public function __construct(
        private readonly ValidatorInterface $stepValidator,
    ) {
    }

    public function getCode(): string
    {
        return 'onestep';
    }

    public function getLabel(): string
    {
        return 'One Step';
    }

    // // @todo: Is this still needed?
    public function isEnabled(): bool
    {
        return true;
    }

    public function isAccessable(): bool
    {
        return true;
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
