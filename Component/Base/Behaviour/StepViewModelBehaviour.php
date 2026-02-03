<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Behaviour;

use Loki\Components\Component\ComponentContextInterface;

/**
 * @method ComponentContextInterface getContext()
 */
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

        $this->step = $this->getContext()->getStepProvider()->getStep($this->getBlock());
        return $this->step;
    }
}
