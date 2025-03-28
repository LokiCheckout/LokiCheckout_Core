<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Yireo\LokiCheckout\Step\FinalStep\Redirect;
use Yireo\LokiCheckout\Validator\QuoteValidator;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Exception\RedirectException;

class FinalStep
{
    public function __construct(
        private QuoteValidator $quoteValidator,
        private CheckoutState $checkoutState,
        private Redirect $redirect,
        private Onepage $onepage,
        private EventManager $eventManager,
    ) {
    }

    public function validate(): true|array
    {
        return $this->quoteValidator->validate();
    }

    public function submit(): bool
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingAddress->getShippingMethod()); // @todo: Is this needed?

        $quote->setInventoryProcessed(false); // @todo: Is this needed?
        $this->onepage->setQuote($quote); // @todo: Is this needed?
        $this->onepage->saveOrder(); // @todo: Remove dependency with OnePage?

        $this->dispatchSaveOrderEvent();

        $redirectUrl = $this->redirect->get();
        throw new RedirectException($redirectUrl);
    }

    private function dispatchSaveOrderEvent()
    {
        $result = new DataObject();
        $result->setData('success', true);
        $result->setData('error', false);
        $this->eventManager->dispatch(
            'checkout_controller_onepage_saveOrder',
            [
                'result' => $result,
                'action' => $this,
            ]
        );
    }
}
