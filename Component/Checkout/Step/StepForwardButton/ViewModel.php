<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\StepForwardButton;

use Exception;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Component\Checkout\Step\StepButtonViewModelInterface;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericViewModel;
use Yireo\LokiCheckout\Step\StepNavigator;

class ViewModel extends GenericViewModel implements StepButtonViewModelInterface
{
    use StepBehaviour;

    public bool $valid = false;

    public function __construct(
        private StepNavigator $stepNavigator,
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
