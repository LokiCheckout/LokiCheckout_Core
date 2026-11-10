<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use LokiCheckout\Core\Config\LayoutConfig;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class LoadLayoutConfig implements ObserverInterface
{
    public function __construct(
        private readonly LayoutConfig $layoutConfig,
    ) {
    }

    public function execute(Observer $observer)
    {
        $layout = $observer->getEvent()->getData('layout');
        if (false === $layout instanceof LayoutInterface) {
            return;
        }

        $this->layoutConfig->loadFromLayout($layout);
    }
}
