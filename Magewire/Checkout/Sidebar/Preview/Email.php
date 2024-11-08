<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class Email extends Component
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    public function __construct(
        private readonly ShippingAddressState $shippingAddressState,
    ) {
    }

    public function getHtml(): string
    {
        return (string)$this->shippingAddressState->getShippingAddress()->getEmail();
    }
}
