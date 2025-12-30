<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class SaveAfterRepositoryDispatch implements ObserverInterface
{
    public function __construct(
        protected SameAddressService $sameAddressService,
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

        if ($this->sameAddressService->isSame()) {
            $this->sameAddressService->copyAddressToAddress();
        }

        $this->checkoutState->saveQuote();
    }
}
