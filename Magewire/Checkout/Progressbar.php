<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Step\StepNavigator;

class Progressbar extends Component
{
    protected $listeners = [
        'afterSaveShippingStep' => 'refresh',
        'afterSaveBillingStep' => 'refresh',
        'afterChangeStep' => 'onStepChange',
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
        $this->emit('afterSaveShippingStep');
        $this->emit('afterSaveBillingStep');
        $this->emit('afterChangeStep', $stepCode);
    }

    public function onStepChange(string $stepCode)
    {
        $this->currentStepCode = $stepCode;
    }
}
