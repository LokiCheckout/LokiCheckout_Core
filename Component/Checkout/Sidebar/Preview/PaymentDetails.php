<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Preview;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ViewModel\ViewModelInterface;

class PaymentDetails  implements ViewModelInterface
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
        } catch(\Exception $e) {
            return '';
        }
    }
}
