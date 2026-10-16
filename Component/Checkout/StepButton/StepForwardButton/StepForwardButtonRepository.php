<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepForwardButton;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Components\Component\ComponentRepository;
use Psr\Log\LoggerInterface;

/**
 * @method CheckoutContext getContext()
 */
class StepForwardButtonRepository extends ComponentRepository
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

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
            if ($this->getStepNavigator()->setNextStep()) {
                $this->getContext()->getSkipValidation()->setSkipValidation(true);
            }

        } catch (UnknownStepException $e) {
            $this->logger->error($e);
            $this->getContext()->getMessageManager()->addErrorMessage(__('Unable to continue to the next step'));
        }
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }
}
