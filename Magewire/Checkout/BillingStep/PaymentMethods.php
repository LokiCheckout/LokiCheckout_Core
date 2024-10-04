<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\BillingStep;

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
    }
}
