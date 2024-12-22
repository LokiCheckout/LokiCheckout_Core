<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Region;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldMutator;

class Mutator extends AddressFieldMutator
{

    public function updatedValue(mixed $value): mixed
    {
        if (is_numeric($value)) {
            $this->addressState->save('region_id', $value);
        }

        $this->setValue($value);
        return $value;
    }
}
