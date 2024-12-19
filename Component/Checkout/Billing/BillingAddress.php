<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddress extends Component
{
    protected $listeners = [
        'afterSaveBillingAddress' => 'refresh',
        'afterSaveBillingStep' => 'refresh',
    ];

    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly BillingAsShippingService $billingAsShippingService,
    ) {
    }

    public function getBillingAsShippingValue(): bool
    {
        return $this->billingAsShippingService->getValueFromAddress(
            $this->billingAddressState->getBillingAddress()
        );
    }

    public function isEnabled(): bool
    {
        return $this->billingAddressState->getQuote()->getGrandTotal() > 0; // @todo: Check how this goes
    }
}
