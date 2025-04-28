<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Behaviour;

trait StepViewModelBehaviour
{
    protected string $step = '';

    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    public function getStep(): string
    {
        if (!empty($this->step)) {
            return $this->step;
        }

        if (false === method_exists($this->getContext(), 'getStepProvider')) {
            throw new \Exception('Component context does not have getStepProvider() method: ' . $this->getComponentName());
        }

        $this->step = $this->getContext()->getStepProvider()->getStep($this->getBlock());
        return $this->step;
    }

    public function getJsData(): array
    {
        return [
            ...parent::getJsData(),
            'step' => $this->getStep(),
        ];
    }
}
