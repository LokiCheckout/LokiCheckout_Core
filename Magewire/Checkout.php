<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\Step\StepNavigator;

class Checkout extends Component
{
    protected $listeners = ['stepChange' => 'refresh'];

    public string $currentStep = '';

    public function __construct(
        private StepNavigator $stepNavigator
    ){
    }

    public function boot(): void
    {
        $this->currentStep = $this->stepNavigator->getCurrentStep()->getCode();
    }
}
