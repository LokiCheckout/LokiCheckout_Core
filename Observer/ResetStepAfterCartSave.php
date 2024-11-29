<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\Manager as MessageManager;
use Yireo\LokiCheckout\Step\StepNavigator;

class ResetStepAfterCartSave implements ObserverInterface
{
    public function __construct(
        private StepNavigator $stepNavigator,
        private MessageManager $messageManager,
    ) {
    }

    public function execute(Observer $observer)
    {
        try {
            $this->stepNavigator->setCurrentStepByCode($this->stepNavigator->getFirstStep()->getCode());
        } catch (\Exception $e) {
            $this->messageManager->addWarningMessage($e->getMessage());
        }
    }
}
