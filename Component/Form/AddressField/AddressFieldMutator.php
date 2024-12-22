<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AddressStateBehaviour;

abstract class AddressFieldMutator extends FieldMutator
{
    use AddressStateBehaviour;

    public function save($value): void
    {
        $this->addressState->save($this->getComponent()->getFieldName(), $value);
    }
}
