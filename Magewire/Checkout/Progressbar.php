<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\Step\StepNavigator;

class Progressbar extends Component
{
    protected $listeners = [
        'quoteSave' => 'refresh',
        'stepChange' => 'onStepChange',
    ];

    public string $currentStepCode = '';

    public function __construct(
        private StepNavigator $stepNavigator
    ) {
    }

    public function boot(): void
    {
        $this->currentStepCode = $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function goToStep(string $stepCode)
    {
        $this->currentStepCode = $stepCode;
        $this->stepNavigator->setCurrentStepByCode($stepCode);
        $this->emit('quoteSave');
        $this->emit('stepChange', $stepCode);
    }

    public function onStepChange(string $stepCode)
    {
        $this->currentStepCode = $stepCode;
    }
}
