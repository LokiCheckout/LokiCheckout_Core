<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use LokiCheckout\Core\Util\Checkout\AddLayoutHandles;
use LokiCheckout\Core\Util\Checkout\AllowCheckoutAccess;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class EnableCheckout implements ObserverInterface
{
    public function __construct(
        private readonly AllowCheckoutAccess $allowCheckoutAccess,
        private readonly AddLayoutHandles $addLayoutHandles
    ) {
    }

    public function execute(Observer $observer)
    {
        if (false === $this->allowCheckoutAccess->allow()) {
            return;
        }

        /** @var LayoutInterface $layout */
        $layout = $observer->getLayout();
        if (false === $layout instanceof LayoutInterface) {
            return;
        }

        if (false === in_array('checkout_index_index', $layout->getUpdate()->getHandles())) {
            return;
        }

        $this->addLayoutHandles->execute();
    }
}
