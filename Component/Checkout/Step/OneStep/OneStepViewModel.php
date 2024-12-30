<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\OneStep;

use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;

use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method OneStepContext getContext()
 */
class OneStepViewModel extends ComponentViewModel implements StepViewModelInterface
{
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
        return $this->getContext()->getStepValidator()->validate();
    }

    public function getJsData(): ?array
    {
        return [
            'step' => $this->getStep(),
        ];
    }

    public function getStep(): string
    {
        return 'none';
    }
}
