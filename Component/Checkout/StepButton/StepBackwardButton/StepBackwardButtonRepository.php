<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepBackwardButton;

use Exception;
use Yireo\LokiCheckout\Component\Checkout\StepButton\StepButtonContext;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method StepButtonContext getContext()
 */
class StepBackwardButtonRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        try {
        return $this->getStepNavigator()->getPreviousStep()->getCode();
        } catch (UnknownStepException $e) {
            return null;
        }
    }

    protected function saveData(mixed $data): void
    {
        try {
            $this->getStepNavigator()->setPreviousStep();
        } catch (Exception $e) {
            $this->getContext()->getMessageManager()->addErrorMessage($e->getMessage());
            return;
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }
}
