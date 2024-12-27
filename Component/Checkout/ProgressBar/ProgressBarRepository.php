<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\ProgressBar;

use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method ProgressBarContext getContext()
 */
class ProgressBarRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        return $this->getContext()->stepNavigator->getCurrentStep()->getCode();
    }

    protected function saveData(mixed $data): void
    {
        $this->getContext()->stepNavigator->setCurrentStepByCode($data);
    }
}
