<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Preview\PaymentDetails;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class PaymentDetailsViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
    ) {
    }

    public function getHtml(): string
    {
        $payment = $this->checkoutSession->getQuote()->getPayment();
        try {
            return (string)$payment->getMethodInstance()->getTitle();
        } catch (\Exception $e) {
            return '';
        }
    }
}
