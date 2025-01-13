<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Api\OrderRepositoryInterface;
use Yireo\LokiCheckout\Step\FinalStep\RedirectUrl;
use Yireo\LokiCheckout\Validator\QuoteValidator;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Exception\RedirectException;

class FinalStep
{
    public function __construct(
        private QuoteValidator $quoteValidator,
        private CheckoutSession $checkoutSession,
        private CheckoutState $checkoutState,
        private RedirectUrl $redirectUrl,
        private Onepage $onepage,
        private EventManager $eventManager,
        private OrderRepositoryInterface $orderRepository,
        private PaymentHelper $paymentHelper
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

        try {
            $order = $this->orderRepository->get($this->checkoutSession->getLastOrderId());
        } catch(\Exception $e) {
            $order = null;
        }

        $paymentCode = $order->getPayment()->getMethod();
        $paymentMethod = $this->paymentHelper->getMethodInstance($paymentCode);

        $this->dispatchSaveOrderEvent();

        $redirectUrl = $this->redirectUrl->get($paymentMethod, $order);
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
