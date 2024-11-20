<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Button;

use Exception;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\ComponentRegistry;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\Step\StepNavigator;

class StepForwardButton extends Component implements ButtonInterface
{
    protected $listeners = [
        'shippingStepSave' => 'refresh',
        'billingStepSave' => 'refresh',
    ];


    public function __construct(
        private StepNavigator $stepNavigator,
        private ComponentRegistry $componentRegistry
    ) {
    }

    public function getComponents(): array
    {
        return $this->componentRegistry->getComponentsByStep($this->getStep());
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
        $this->clearFlashMessages();
        $this->emit('stepValidate', $this->getStep());

        try {
            $result = $this->stepNavigator->setNextStep($this);
        } catch (Exception $e) {
            $this->dispatchErrorMessage($e->getMessage());
            return;
        }

        if (true === $result) {
            $this->emit('stepChange', $this->getStep());
        }
    }

    public function isVisible(): bool
    {
        return true;
    }
}
