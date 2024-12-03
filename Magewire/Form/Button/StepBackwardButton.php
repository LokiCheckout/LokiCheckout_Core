<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

use Exception;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Step\StepNavigator;

class StepBackwardButton extends Component implements ButtonInterface
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

    public function getElementId(): string
    {
        $elementId = $this->getParent()->getNameInLayout();
        return preg_replace('#([^a-zA-Z0-9]{1})#', '-', $elementId);
    }

    public function getStep(): string
    {
        return $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function getJsName(): string
    {
        $name = ucwords($this->getParent()->getNameInLayout(), '_.-');

        return preg_replace('#[_.-]+#', '', $name);
    }

    public function submit()
    {
        try {
            $this->stepNavigator->setPreviousStep();
        } catch (Exception $e) {
            $this->dispatchErrorMessage($e->getMessage());
            return;
        }

        $this->emit('afterChangeStep', $this->getStep());
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isVisible(): bool
    {
        if ($this->stepNavigator->isFirstStep($this->stepNavigator->getCurrentStep())) {
            return false;
        }

        return true;
    }
}
