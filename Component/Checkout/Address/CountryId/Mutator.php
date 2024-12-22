<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\CountryId;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldMutator;

class Mutator extends AddressFieldMutator
{

    protected array $filters = ['country_id'];

    public function beforeSave(mixed $value): void
    {
        $this->addressState->save('region', '');
        $this->addressState->save('region_id', '');
        $this->setValue($value);

        return;
    }
}
