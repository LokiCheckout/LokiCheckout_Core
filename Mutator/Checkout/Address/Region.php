<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Checkout\Address;

use Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

class Region extends AddressField
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
