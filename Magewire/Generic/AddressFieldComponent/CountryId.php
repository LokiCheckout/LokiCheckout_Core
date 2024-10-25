<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class CountryId extends AddressFieldComponent implements FormSelectFieldInterface
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
