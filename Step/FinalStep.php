<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\Exception\PaymentException;
use Magento\Framework\UrlFactory;
use Magento\Quote\Model\QuoteManagement;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Validator\QuoteValidator;

class FinalStep
{
    public function __construct(
        private QuoteValidator $quoteValidator,
        private CheckoutSession $checkoutSession,
        private Onepage $onepage,
        private EventManager $eventManager,
        private UrlFactory $urlFactory
    ) {
    }

    public function validate(): true|array
    {
        // @todo: Implement agreements
        /*
         $agreementsValidator = $this->_objectManager->get(
                \Magento\Checkout\Api\AgreementsValidatorInterface::class
            );
            if (!$agreementsValidator->isValid(array_keys($this->getRequest()->getPost('agreement', [])))) {
                $result->setData('success', false);
                $result->setData('error', true);
                $result->setData(
                    'error_messages',
                    __(
                        "The order wasn't placed. "
                        . "First, agree to the terms and conditions, then try placing your order again."
                    )
                );
         */

        return $this->quoteValidator->validate();
    }

    public function submit(Component $source): bool
    {
        $quote = $this->checkoutSession->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingAddress->getShippingMethod()); // @todo: Is this needed?

        $quote->setInventoryProcessed(false); // @todo: Is this needed?
        $this->onepage->setQuote($quote); // @todo: Is this needed?
        $this->onepage->saveOrder();

        $this->dispatchSaveOrderEvent();
        $source->redirect($this->getRedirectUrl());

        return true;
    }

    private function getRedirectUrl(): string
    {
        $redirectUrl = (string)$this->checkoutSession->getRedirectUrl();
        if (!empty($redirectUrl)) {
            return $redirectUrl;
        }

        $url = $this->urlFactory->create();

        return $url->getUrl('checkout/onepage/success');
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
