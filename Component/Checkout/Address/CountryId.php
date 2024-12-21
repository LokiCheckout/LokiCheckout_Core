<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

class CountryId extends AddressField
{
    use OptionsBehaviour;

    protected array $filters = ['country_id'];

    public function updatedValue(mixed $value): mixed
    {
        $this->addressState->save('region', '');
        $this->addressState->save('region_id', '');
        $this->setValue($value);

        return $value;
    }
}
