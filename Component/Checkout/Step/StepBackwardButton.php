<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step;

use Exception;
use Yireo\LokiCheckout\Component\Form\Button\ButtonInterface;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Step\StepNavigator;

class StepBackwardButton extends Generic implements ButtonInterface
{
    use StepBehaviour;

    public function __construct(
        private StepNavigator $stepNavigator,
        private MessageManager $messageManager,
    ) {
    }

    public function getCurrentStep(): string
    {
        return $this->stepNavigator->getCurrentStep()->getCode();
    }

    public function submit()
    {
        try {
            $this->stepNavigator->setPreviousStep();
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return;
        }

        // @todo: Trigger afterChangeStep()
    }

    public function isAllowRendering(): bool
    {
        if ($this->stepNavigator->isFirstStep($this->stepNavigator->getStepByCode($this->getStep()))) {
            return false;
        }

        return true;
    }
}
