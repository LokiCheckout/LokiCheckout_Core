<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep;

use Magewirephp\Magewire\Component;

class BillingAddress extends Component
{
    protected $listeners = ['billingStepSave' => 'refresh'];
}
