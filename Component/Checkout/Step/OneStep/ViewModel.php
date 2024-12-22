<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\OneStep;

use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericViewModel;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class ViewModel extends GenericViewModel implements StepViewModelInterface
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
