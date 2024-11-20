<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddress extends Component
{
    protected $listeners = [
        'billingStepSave' => 'refresh'
    ];

    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly AddressRenderer $addressRenderer
    ) {
    }

    public function isSameAsShipping(): bool
    {
        return (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
    }

    public function getHtml(): string
    {
        $address = $this->billingAddressState->getBillingAddress();
        return $this->addressRenderer->getHtml($address);
    }
}
