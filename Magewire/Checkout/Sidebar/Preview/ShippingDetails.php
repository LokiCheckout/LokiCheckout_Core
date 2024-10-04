<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

class ShippingDetails extends Component
{
    protected $listeners = ['quoteSave' => 'refresh'];

    public function __construct(
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function getHtml(): string
    {
        $address = $this->checkoutState->getQuote()->getShippingAddress();

        $method = $address->getShippingMethod();
        if (empty($method)) {
            return '';
        }

        $carrier = $address->getShippingRateByCode($method);
        if (!empty($carrier)) {
            return $carrier->getCarrierTitle().' ['.$method.']';
        }

        return $method;
    }
}
