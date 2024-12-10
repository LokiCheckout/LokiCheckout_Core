<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

use Exception;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepNavigator;

class StepBackwardButton extends GenericComponent implements ButtonInterface
{
    use AlpineDataBehaviour;

    public string $value = '';
    public bool $valid = true;

    protected $listeners = [
        'afterChangeStep' => 'refresh',
    ];

    public function __construct(
        private StepNavigator $stepNavigator,
    ) {
    }

    public function mount(): void
    {
        if ($this->stepNavigator->isFirstStep($this->stepNavigator->getCurrentStep())) {
            $this->allowRendering = false;
        }

        parent::mount();
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

        $this->emit('afterChangeStep', $this->getCurrentStep());
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getStep(): string
    {
        return 'any';
    }
}
