<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step;

use LokiCheckout\Core\Step\FinalStep\TaskListing;
use Magento\Checkout\Api\Exception\PaymentProcessingRateLimitExceededException;
use Magento\Checkout\Api\PaymentProcessingRateLimiterInterface;
use Magento\Checkout\Helper\Data as CheckoutHelper;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Framework\UrlFactory;
use Magento\Framework\Validator\Exception;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Psr\Log\LoggerInterface;
use LokiCheckout\Core\Step\FinalStep\Redirect;
use LokiCheckout\Core\Validator\QuoteValidator;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Exception\RedirectException;
use Throwable;

class FinalStep
{
    public function __construct(
        private readonly QuoteValidator $quoteValidator,
        private readonly CheckoutState $checkoutState,
        private readonly Redirect $redirect,
        private readonly EventManager $eventManager,
        private readonly Manager $messageManager,
        private readonly CartManagementInterface $cartManagement,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderSender $orderSender,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly TaskListing $taskListing,
        private readonly UrlFactory $urlFactory,
        private readonly CheckoutHelper $checkoutHelper,
        private readonly LoggerInterface $logger,
        private readonly PaymentProcessingRateLimiterInterface $paymentRateLimiter,
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

    /**
     * @param bool $redirect
     *
     * @return bool
     * @throws CouldNotSaveException
     * @throws RedirectException
     */
    public function submit(bool $redirect = true): bool
    {
        $this->checkoutState->reset();
        $quote = $this->checkoutState->getPersistedQuote();
        $this->checkoutState->setQuote($quote);

        $this->assertGuestCheckoutAllowed($quote);
        $this->limitPaymentProcessing();

        if (!$quote->isVirtual()) {
            $shippingAddress = $quote->getShippingAddress();
            $shippingMethod = $shippingAddress->getShippingMethod();

            if (empty($shippingMethod)) {
                $rates = $shippingAddress->getAllShippingRates();
                if (!empty($rates)) {
                    $firstRate = reset($rates);
                    $shippingMethod = $firstRate->getCode();
                }
            }

            if (!empty($shippingMethod)) {
                $shippingAddress->setShippingMethod($shippingMethod);
                $shippingAddress->save(); // @phpstan-ignore-line
            }

            $quote->setInventoryProcessed(false);
        }

        $this->processGuestCart($quote);

        foreach ($this->taskListing->getTasks() as $task) {
            $task->beforePlaceOrder($quote);
        }

        $this->cartRepository->save($quote);

        try {
            $orderId = (int)$this->cartManagement->placeOrder($quote->getId(), $quote->getPayment());
        } catch (LocalizedException $e) {
            $msg = __('An error occurred while placing the order.') .' '. $e->getMessage();
            $this->messageManager->addErrorMessage($msg);
            throw $this->getCheckoutRedirect();
        } catch (Throwable $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('An error occurred while placing the order.'));
            throw $this->getCheckoutRedirect();
        }

        foreach ($this->taskListing->getTasks() as $task) {
            $task->afterPlaceOrder($orderId);
        }

        $this->updateCheckoutSessionFromCart($quote);

        if ($orderId !== 0) {
            /** @var Order $order */
            $order = $this->orderRepository->get($orderId);
            $this->dispatchCheckoutTypeOnepageSaveOrderAfter($order);
            $this->updateCheckoutSessionFromOrder($order);
        }

        $this->dispatchSaveOrderEvent();

        if (true === $redirect) {
            throw (new RedirectException())->setUrl($this->redirect->get());
        }

        return true;
    }

    /**
     * @throws RedirectException
     */
    private function assertGuestCheckoutAllowed(Quote $quote): void
    {
        if ($this->checkoutState->getCustomerSession()->isLoggedIn()) {
            return;
        }

        if ($this->checkoutHelper->isAllowedGuestCheckout($quote)) {
            return;
        }

        $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));
        throw (new RedirectException())->setUrl($this->urlFactory->create()->getUrl('checkout/cart'));
    }

    /**
     * @throws RedirectException
     */
    private function limitPaymentProcessing(): void
    {
        try {
            $this->paymentRateLimiter->limit();
        } catch (PaymentProcessingRateLimitExceededException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            throw $this->getCheckoutRedirect();
        }
    }

    private function getCheckoutRedirect(): RedirectException
    {
        $checkoutUrl = $this->urlFactory->create()->getUrl('checkout');

        return (new RedirectException())->setUrl($checkoutUrl);
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
        if ($this->checkoutState->getCustomerSession()->isLoggedIn()) {
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

    // @todo: Remove this when ready
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
