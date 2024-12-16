<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddress extends Component
{
    protected $listeners = [
        'afterSaveBillingAddress' => 'refresh',
        'afterSaveBillingStep' => 'refresh',
    ];

    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly Config $config
    ) {
    }

    public function getBillingAsShippingValue(): bool
    {
        if (false === $this->config->enableSameAsBilling()) {
            return false;
        }

        return (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
    }

    public function isEnabled(): bool
    {
        return $this->billingAddressState->getQuote()->getGrandTotal() > 0;
    }
}
