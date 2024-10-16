<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PaymentDetails extends Component
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public function __construct(
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function getHtml(): string
    {
        $payment = $this->checkoutState->getQuote()->getPayment();
        return $payment->getIdFieldName();
    }
}
