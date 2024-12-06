<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;

class Totals extends Component
{
    protected $listeners = [
        'afterSaveQuote' => 'refresh',
        'afterSavePaymentMethod' => 'refresh',
        'afterSaveBillingStep' => 'refresh',
        'afterSaveBillingAddress' => 'refresh',
        'afterSaveShippingStep' => 'refresh',
        'afterSaveShippingAddress' => 'refresh',
    ];
}
