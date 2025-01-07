<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepForwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method GenericContext getContext()
 */
class StepForwardButtonRepository extends ComponentRepository
{
    protected function getValue(): mixed
    {
        try {
            return $this->getContext()->getStepNavigator()->getNextStep()->getCode();
        } catch(UnknownStepException $e) {
            return null;
        }
    }

    protected function saveValue(mixed $data): void
    {
        try {
            $this->getStepNavigator()->setNextStep();
        } catch (UnknownStepException $e) {
            $this->getContext()->getGlobalMessageRegistry()->addError($e->getMessage());
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }
}
