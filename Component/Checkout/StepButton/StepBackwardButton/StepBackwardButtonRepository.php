<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepBackwardButton;

use Exception;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method GenericContext getContext()
 */
class StepBackwardButtonRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        try {
        return $this->getStepNavigator()->getPreviousStep()->getCode();
        } catch (UnknownStepException $e) {
            return null;
        }
    }

    public function saveValue(mixed $value): void
    {
        try {
            $this->getStepNavigator()->setPreviousStep();
        } catch (Exception $e) {
            $this->getContext()->getGlobalMessageRegistry()->addError($e->getMessage()); // @todo: Only in Developer Mode
            return;
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }
}
