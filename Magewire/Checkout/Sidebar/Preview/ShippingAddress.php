<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ShippingAddress extends Component
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    public function __construct(
        private readonly ShippingAddressState $shippingAddressState,
        private readonly AddressRenderer $addressRenderer

    ) {
    }

    public function getHtml(): string
    {
        if ($this->shippingAddressState->getQuote()->isVirtual()) {
            return '';
        }

        $address = $this->shippingAddressState->getShippingAddress();
        return $this->addressRenderer->getHtml($address);
    }
}
