<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Loki\Components\Util\Controller\ComponentUpdate;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class SaveAfterRepositoryDispatch implements ObserverInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function execute(Observer $observer)
    {
        /** @var LayoutInterface $layout */
        $layout = $observer->getLayout();
        if (false === in_array('loki_checkout', $layout->getUpdate()->getHandles())) {
            return;
        }

        $this->checkoutState->saveQuote();
    }
}
