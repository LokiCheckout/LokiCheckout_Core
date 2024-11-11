<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Step\StepNavigator;

class Checkout extends Component
{
    protected $listeners = ['stepChange' => 'stepChange'];

    public string $currentStep = '';

    public function __construct(
        private StepNavigator $stepNavigator
    ){
    }

    public function boot(): void
    {
        $this->currentStep = $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function stepChange()
    {
        $this->currentStep = $this->stepNavigator->getCurrentStep()->getCode();
    }
}
