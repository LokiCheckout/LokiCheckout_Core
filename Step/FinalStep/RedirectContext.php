<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class RedirectContext
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly PaymentHelper $paymentHelper,
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function getPaymentMethod(): false|MethodInterface
    {
        $order = $this->getOrder();
        if (false === $order) {
            return false;
        }

        $payment = $order->getPayment();
        if (false === $payment instanceof OrderPaymentInterface) {
            return false;
        }

        $paymentCode = $payment->getMethod();
        if (empty($paymentCode)) {
            return false;
        }

        try {
            return $this->paymentHelper->getMethodInstance($paymentCode);
        } catch (LocalizedException $e) {
            return false;
        }
    }

    public function getOrder(): false|OrderInterface
    {
        $lastOrderId = $this->checkoutState->getCheckoutSession()->getLastOrderId();
        if (empty($lastOrderId)) {
            return false;
        }

        return $this->orderRepository->get($lastOrderId);
    }
}
