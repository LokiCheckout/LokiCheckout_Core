<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Checkout\Address;

use Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

class CountryId extends AddressField
{

    protected array $filters = ['country_id'];

    public function beforeSave(mixed $value): void
    {
        $this->addressState->save('region', '');
        $this->addressState->save('region_id', '');
        $this->setValue($value);

        // @todo: THis does not work yet

        return $value;
    }
}
