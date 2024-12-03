<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magewirephp\Magewire\Component;

class PaymentDetails extends Component
{
    protected $listeners = [
        'afterSaveBillingStep' => 'refresh',
        'afterSaveBillingAddress' => 'refresh',
        'afterSavePaymentMethod' => 'refresh',
        'afterSaveQuote' => 'refresh',
    ];

    public function __construct(
        private readonly CheckoutSession $checkoutSession,
    ) {
    }

    public function getHtml(): string
    {
        $payment = $this->checkoutSession->getQuote()->getPayment();
        try {
            return $payment->getMethodInstance()->getTitle();
        } catch(\Exception $e) {
            return '';
        }
    }
}
