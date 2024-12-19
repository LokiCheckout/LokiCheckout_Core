<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingView  extends Component
{
    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly AddressRenderer $addressRenderer
    ) {
    }

    public function getHtml(): string
    {
        $address = $this->billingAddressState->getBillingAddress();
        return $this->addressRenderer->getHtml($address);
    }
}
