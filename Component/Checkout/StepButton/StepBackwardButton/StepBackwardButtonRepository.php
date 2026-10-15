<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepBackwardButton;

use Exception;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Components\Component\ComponentRepository;
use Psr\Log\LoggerInterface;

/**
 * @method CheckoutContext getContext()
 */
class StepBackwardButtonRepository extends ComponentRepository
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

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
            $this->logger->error($e);
            $this->getContext()->getGlobalMessageRegistry()->addError((string)__('Unable to go back to the previous step'));
            return;
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }
}
