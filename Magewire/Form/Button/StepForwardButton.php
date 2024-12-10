<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

use Exception;
use Yireo\LokiCheckout\Magewire\ComponentRegistry;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepNavigator;

class StepForwardButton extends GenericComponent implements ButtonInterface
{
    use AlpineDataBehaviour;

    public string $value = '';
    public bool $valid = false;

    protected $listeners = [
        'afterChangeStep' => 'refresh',
    ];

    public function __construct(
        private StepNavigator $stepNavigator,
        private ComponentRegistry $componentRegistry
    ) {
    }

    public function getComponents(): array
    {
        return $this->componentRegistry->getComponentsByStep($this->getCurrentStep());
    }

    public function getCurrentStep(): string
    {
        return $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function submit()
    {
        $this->clearFlashMessages();
        $this->emit('afterValidateStep', $this->getCurrentStep());

        try {
            $result = $this->stepNavigator->setNextStep($this);
        } catch (Exception $e) {
            $this->dispatchErrorMessage($e->getMessage());
            return;
        }

        if (true === $result) {
            $this->emit('afterChangeStep', $this->getCurrentStep());
        }
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
