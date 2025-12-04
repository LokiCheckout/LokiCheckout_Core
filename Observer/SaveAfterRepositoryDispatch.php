<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveAfterRepositoryDispatch implements ObserverInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function execute(Observer $observer)
    {
        $this->checkoutState->saveQuote();
    }
}
