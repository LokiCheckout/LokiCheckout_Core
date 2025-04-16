<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
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
        private EventManager $eventManager,
        private CartManagementInterface $cartManagement,
        private OrderRepositoryInterface $orderRepository,
        private OrderSender $orderSender
    ) {
    }

    public function validate(): true|array
    {
        return $this->quoteValidator->validate();
    }

    public function submit(): bool
    {
        $quote = $this->checkoutState->getQuote();
        if (!$quote->isVirtual()) {
            $shippingAddress = $quote->getShippingAddress();
            $shippingAddress
                ->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod($shippingAddress->getShippingMethod());
            $quote->setInventoryProcessed(false);
        }

        $this->processGuestCart($quote);
        $orderId = (int)$this->cartManagement->placeOrder($quote->getId(), $quote->getPayment());
        $this->updateCheckoutSessionFromCart($quote);

        if ($orderId) {
            $order = $this->orderRepository->get($orderId);
            $this->dispatchCheckoutTypeOnepageSaveOrderAfter($order);
            $this->updateCheckoutSessionFromOrder($order);
            $this->sendMail($order);
        }

        $this->dispatchSaveOrderEvent();

        $redirectUrl = $this->redirect->get();
        throw new RedirectException($redirectUrl);
    }

    private function dispatchCheckoutTypeOnepageSaveOrderAfter(OrderInterface $order): void
    {
        $quote = $this->checkoutState->getQuote();
        $this->eventManager->dispatch(
            'checkout_type_onepage_save_order_after',
            ['order' => $order, 'quote' => $quote]
        );
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

    private function processGuestCart(CartInterface $cart): void
    {
        if (false === (bool)$cart->getCustomerIsGuest()) {
            return;
        }

        $cart->setCustomerId(0)
            ->setCustomerEmail($cart->getBillingAddress()->getEmail())
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(GroupInterface::NOT_LOGGED_IN_ID);
    }

    private function updateCheckoutSessionFromCart(CartInterface $cart): void
    {
        $checkoutSession = $this->checkoutState->getCheckoutSession();
        $checkoutSession
            ->setLastQuoteId($cart->getId())
            ->setLastSuccessQuoteId($cart->getId())
            ->clearHelperData();
    }

    private function updateCheckoutSessionFromOrder(OrderInterface $order): void
    {
        $quote = $this->checkoutState->getQuote();
        $redirectUrl = $quote->getPayment()->getOrderPlaceRedirectUrl();
        $checkoutSession = $this->checkoutState->getCheckoutSession();
        $checkoutSession
            ->setLastOrderId($order->getId())
            ->setRedirectUrl($redirectUrl)
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());
    }

    private function sendMail(OrderInterface $order): void
    {
        $quote = $this->checkoutState->getQuote();
        $redirectUrl = $quote->getPayment()->getOrderPlaceRedirectUrl();
        if ($redirectUrl) {
            return;
        }

        if (false === $order->getCanSendNewEmailFlag()) {
            return;
        }

        /** @var Order $order */
        try {
            $this->orderSender->send($order);
        } catch (\Exception $e) {
        }
    }
}
