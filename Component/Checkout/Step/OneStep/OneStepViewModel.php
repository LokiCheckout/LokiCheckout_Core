<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step\OneStep;

use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;

use Loki\Components\Component\ComponentViewModel;

/**
 * @method OneStepContext getContext()
 */
class OneStepViewModel extends ComponentViewModel implements StepViewModelInterface
{
    public function getCode(): string
    {
        return 'one';
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

    public function validate(): bool|array
    {
        return $this->getContext()->getStepValidator()->validate();
    }

    public function getJsData(): array
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
