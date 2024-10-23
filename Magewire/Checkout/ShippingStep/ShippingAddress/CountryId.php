<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;

class CountryId extends ShippingAddressFieldComponent implements FormSelectFieldInterface
{
    use OptionsBehaviour;

    protected array $filters = ['country_id'];

    public function updatedValue($value)
    {
        $this->addressState->save('region', '');
        $this->addressState->save('region_id', '');
        $this->setValue($value);
        return $value;
    }
}
