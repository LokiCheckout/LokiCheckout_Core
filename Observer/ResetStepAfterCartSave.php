<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Step\StepNavigator;

class ResetStepAfterCartSave implements ObserverInterface
{
    public function __construct(
        private readonly StepNavigator $stepNavigator,
        private readonly LoggerInterface $logger,
        private readonly Config $config
    ) {
    }

    public function execute(Observer $observer)
    {
        if (false === $this->config->isEnabled()) {
            return;
        }

        try {
            $this->stepNavigator->setCurrentStepByCode($this->stepNavigator->getFirstStep()->getCode());
        } catch (\Exception $e) {
            $this->logger->warning($e);
        }
    }
}
