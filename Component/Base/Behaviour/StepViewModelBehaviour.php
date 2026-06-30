<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Behaviour;

use Loki\Components\Attribute\JsProperty;

trait StepViewModelBehaviour
{
    protected string $step = '';

    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    #[JsProperty(name: 'step')]
    public function getStep(): string
    {
        if (!empty($this->step)) {
            return $this->step;
        }

        $this->step = $this->getContext()->getStepProvider()->getStep($this->getBlock());
        return $this->step;
    }
}
