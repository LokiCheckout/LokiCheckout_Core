<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Form\Field;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AddressStateBehaviour;

abstract class AddressField extends Field
{
    use AddressStateBehaviour;

    public function save($value): void
    {
        $this->addressState->save($this->getComponent()->getFieldName(), $value);
    }
}
