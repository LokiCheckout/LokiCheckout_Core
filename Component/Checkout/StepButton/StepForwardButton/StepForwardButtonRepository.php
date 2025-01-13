<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepForwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method CheckoutContext getContext()
 */
class StepForwardButtonRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        try {
            return $this->getContext()->getStepNavigator()->getNextStep()->getCode();
        } catch(UnknownStepException $e) {
            return null;
        }
    }

    public function saveValue(mixed $value): void
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
