<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiComponents\Component\ViewModel as ParentViewModel;

class ViewModel extends ParentViewModel
{
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
