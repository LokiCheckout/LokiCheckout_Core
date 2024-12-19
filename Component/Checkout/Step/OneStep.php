<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step;

use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class OneStep extends Generic implements StepInterface
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
