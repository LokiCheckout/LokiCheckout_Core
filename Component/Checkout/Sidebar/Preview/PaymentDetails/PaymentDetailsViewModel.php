<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Preview\PaymentDetails;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PaymentDetailsViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function getHtml(): string
    {
        $payment = $this->checkoutState->getQuote()->getPayment();
        try {
            return (string)$payment->getMethodInstance()->getTitle();
        } catch (\Exception $e) {
            return '';
        }
    }
}
