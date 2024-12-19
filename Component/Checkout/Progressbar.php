<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout;

use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\MutableComponentInterface;

class Progressbar implements MutableComponentInterface
{
    public string $currentStepCode = '';

    public function __construct(
        private StepNavigator $stepNavigator
    ) {
    }

    public function getCurrentStep()
    {
        return $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function mutate(array $data = []): void
    {
        $this->stepNavigator->setCurrentStepByCode($data['step']);
    }
}
