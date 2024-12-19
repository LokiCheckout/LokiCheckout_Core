<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Button;

use Exception;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRegistry;

class StepForwardButton extends Generic implements ButtonInterface
{
    use StepBehaviour;

    public bool $valid = false;

    public function __construct(
        private StepNavigator $stepNavigator,
        private ComponentRegistry $componentRegistry,
        private MessageManager $messageManager,
    ) {
    }

    public function submit()
    {
        try {
            $result = $this->stepNavigator->setNextStep();
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return;
        }

        if (true === $result) {
            // @todo: Trigger afterChangeStep()
        }
    }
}
