<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use LokiCheckout\Core\Component\PostDispatchHandler\PostDispatchHandlerInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class SaveAfterRepositoryDispatch implements ObserverInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly array $postDispatchHandlers = []
    ) {
    }

    public function execute(Observer $observer)
    {
        /** @var LayoutInterface $layout */
        $layout = $observer->getLayout();
        if (false === $layout instanceof LayoutInterface) {
            return;
        }

        if (false === in_array('loki_checkout', $layout->getUpdate()->getHandles())) {
            return;
        }

        $updates = $observer->getUpdates();
        if (false === is_array($updates)) {
            return;
        }

        foreach ($this->postDispatchHandlers as $handler) {
            if (false === $handler instanceof PostDispatchHandlerInterface) {
                continue;
            }

            $handler->execute($updates);
        }

        $this->checkoutState->saveQuote();
    }
}
