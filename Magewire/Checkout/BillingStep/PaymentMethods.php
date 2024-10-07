<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep;

use Magewirephp\Magewire\Component;

class PaymentMethods extends Component
{
    public array $paymentMethods = [];
    public string $value = '';

    public function boot()
    {
    }

    public function updatedValue(string $value)
    {
        return $value;
        $quote->setPaymentMethod('checkmo');
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => 'checkmo']);
        $quote->collectTotals()->save();

    }
}
