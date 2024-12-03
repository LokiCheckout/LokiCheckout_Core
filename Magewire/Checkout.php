<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Step\StepNavigator;

class Checkout extends Component
{
    protected $listeners = [
        'afterChangeStep' => 'onStepChange'
    ];

    public string $currentStep = '';

    public function __construct(
        private StepNavigator $stepNavigator
    ){
    }

    public function mount(): void
    {
        $this->currentStep = $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function onStepChange()
    {
        $this->currentStep = $this->stepNavigator->getCurrentStep()->getCode();
    }
}
