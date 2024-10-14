<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Step\StepNavigator;

class Progressbar extends Component
{
    protected $listeners = [
        'shippingStepSave' => 'refresh',
        'billingStepSave' => 'refresh',
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
        $this->emit('shippingStepSave'); // @todo: Pick the right event only
        $this->emit('billingStepSave');
        $this->emit('stepChange', $stepCode);
    }

    public function onStepChange(string $stepCode)
    {
        $this->currentStepCode = $stepCode;
    }
}
