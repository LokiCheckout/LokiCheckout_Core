<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepForwardButton;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Components\Component\ComponentRepository;

/**
 * @method CheckoutContext getContext()
 */
class StepForwardButtonRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        try {
            return $this->getContext()->getStepNavigator()->getNextStep()->getCode();
        } catch (UnknownStepException $e) {
            return null;
        }
    }

    public function saveValue(mixed $value): void
    {
        try {
            $this->getStepNavigator()->setNextStep();
            $this->getContext()->getSkipValidation()->setSkipValidation(true);

        } catch (UnknownStepException $e) {
            $this->getContext()->getGlobalMessageRegistry()->addError('UnknownStepException: ' . $e->getMessage());
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }
}
