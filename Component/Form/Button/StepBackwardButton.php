<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

use Exception;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepNavigator;

class StepBackwardButton extends GenericComponent implements ButtonInterface
{
    use AlpineDataBehaviour;
    use StepBehaviour;

    public bool $valid = true;

    protected $listeners = [
        'afterChangeStep' => 'refresh',
    ];

    public function __construct(
        private StepNavigator $stepNavigator,
    ) {
    }

    public function boot(): void
    {
        if ($this->stepNavigator->isFirstStep($this->stepNavigator->getStepByCode($this->getStep()))) {
            $this->allowRendering = false;
        }

        parent::boot();
    }

    public function getCurrentStep(): string
    {
        return $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function submit()
    {
        try {
            $this->stepNavigator->setPreviousStep();
        } catch (Exception $e) {
            $this->dispatchErrorMessage($e->getMessage());
            return;
        }

        $this->emit('afterChangeStep', $this->stepNavigator->getCurrentStep()->getCode());
    }
}
