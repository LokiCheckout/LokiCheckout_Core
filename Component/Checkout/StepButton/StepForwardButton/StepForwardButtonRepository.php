<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\StepForwardButton;

use Exception;
use Yireo\LokiCheckout\Component\Checkout\StepButton\StepButtonContext;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method StepButtonContext getContext()
 */
class StepForwardButtonRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        return $this->getContext()->stepNavigator->getNextStep()->getCode();
    }

    protected function saveData(mixed $data): void
    {
        try {
            $this->getStepNavigator()->setNextStep();
        } catch (Exception $e) {
            $this->getContext()->messageManager->addErrorMessage($e->getMessage());
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->stepNavigator;
    }
}
