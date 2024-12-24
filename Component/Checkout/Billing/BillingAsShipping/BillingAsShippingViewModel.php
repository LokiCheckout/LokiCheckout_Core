<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAsShipping;

use Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress\BillingAddressContext;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method BillingAddressContext getContext()
 */
class BillingAsShippingViewModel extends ComponentViewModel
{
    public function isBillingAsShipping(): bool
    {
        return (bool)$this->getRepository()->get();
    }

    // @todo: Rewrite this somehow?
    public function getAlpineData(): array
    {
        return [
            'billingAsShipping' => $this->isBillingAsShipping()
        ];
    }

}
