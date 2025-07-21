<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepBackwardButton;

use Exception;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Components\Component\ComponentRepository;

/**
 * @method CheckoutContext getContext()
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
