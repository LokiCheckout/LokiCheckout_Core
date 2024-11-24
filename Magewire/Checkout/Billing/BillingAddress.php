<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddress extends Component
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly BillingAsShippingService $billingAsShippingService,
    ) {
    }

    public function getBillingAsShippingValue(): bool
    {
        if (false === $this->billingAsShippingService->isEnabled()) {
            return false;
        }

        return (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
    }

    public function isEnabled(): bool
    {
        return $this->hasPayableAmount();
    }

    private function hasPayableAmount(): bool
    {
        return $this->billingAddressState->getQuote()->getGrandTotal() > 0;
    }
}
