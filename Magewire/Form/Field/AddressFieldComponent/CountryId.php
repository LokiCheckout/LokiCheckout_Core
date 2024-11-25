<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FormSelectFieldInterface;

class CountryId extends AddressFieldComponent implements FormSelectFieldInterface
{
    use OptionsBehaviour;

    protected array $filters = ['country_id'];

    public function updatedValue(mixed $value): mixed
    {
        $this->addressState->save('region', '');
        $this->addressState->save('region_id', '');
        $this->setValue($value);
        $this->emit('countrySave'); // @todo: Standardize event names
        return $value;
    }
}
