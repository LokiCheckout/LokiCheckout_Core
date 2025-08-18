<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validator\Exception;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use LokiCheckout\Core\Step\FinalStep\Redirect;
use LokiCheckout\Core\Validator\QuoteValidator;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Exception\RedirectException;

class FinalStep
{
    public function __construct(
        private readonly QuoteValidator $quoteValidator,
        private readonly CheckoutState $checkoutState,
        private readonly CustomerSession $customerSession,
        private readonly Redirect $redirect,
        private readonly EventManager $eventManager,
        private readonly CartManagementInterface $cartManagement,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderSender $orderSender,
        private readonly CartRepositoryInterface $cartRepository
    ) {
    }

    /**
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function validate(): bool|array
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
        $this->cartRepository->save($quote);

        $orderId = (int)$this->cartManagement->placeOrder($quote->getId(), $quote->getPayment());
        $this->updateCheckoutSessionFromCart($quote);

        if ($orderId !== 0) {
            /** @var Order $order */
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

    private function processGuestCart(CartInterface $quote): void
    {
        if ($this->customerSession->isLoggedIn()) {
            return;
        }

        /** @var Quote $quote */
        $quote->setCustomerId(0)
            ->setCustomerEmail($quote->getBillingAddress()->getEmail())
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

    private function updateCheckoutSessionFromOrder(Order $order): void
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

    private function sendMail(Order $order): void
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
