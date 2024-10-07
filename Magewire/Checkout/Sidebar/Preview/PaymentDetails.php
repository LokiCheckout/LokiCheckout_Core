<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class PaymentDetails extends Component
{
    protected $listeners = ['quoteSave' => 'refresh'];

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
