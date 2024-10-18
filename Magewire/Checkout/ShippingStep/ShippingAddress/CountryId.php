<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;

class CountryId extends ShippingAddressFieldComponent implements FormSelectFieldInterface
{
    use OptionsBehaviour;

    public function updatedValue($value)
    {
        $this->shippingAddressState->save('region', '');
        $this->shippingAddressState->save('region_id', '');
        return parent::updatedValue($value);
    }
}
