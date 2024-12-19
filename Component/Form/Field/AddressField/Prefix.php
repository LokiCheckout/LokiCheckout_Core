<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\AddressField;

use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

abstract class Prefix extends AddressField
{
    use OptionsBehaviour;

    protected array $validators = ['prefix'];

    public function getFieldLabel(): string
    {
        return 'Prefix';
    }
}
