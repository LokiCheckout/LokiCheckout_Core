<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Preview\PaymentDetails;

use Exception;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;

class PaymentDetailsViewModel implements ArgumentInterface
{
    protected bool $skipValidation = true;
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly Escaper $escaper,
    ) {
    }

    public function getHtml(): string
    {
        $payment = $this->checkoutState->getQuote()->getPayment();
        try {
            return $this->escaper->escapeHtml((string)$payment->getMethodInstance()->getTitle());
        } catch (Exception $e) {
            return '';
        }
    }

    public function getPaymentCode(): string
    {
        try {
            return $this->checkoutState->getQuote()->getPayment()->getMethod();
        } catch (Exception $e) {
        }

        return '';
    }
}
