<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Loki\Components\Layout\LayoutHandlerComposite;
use LokiCheckout\Core\Util\Checkout\AllowCheckoutAccess;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class EnableCheckout implements ObserverInterface
{
    public function __construct(
        private readonly AllowCheckoutAccess $allowCheckoutAccess,
        private readonly LayoutHandlerComposite $layoutHandlerComposite,
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

        $this->layoutHandlerComposite->execute($layout->getUpdate()->getHandles());
    }
}
