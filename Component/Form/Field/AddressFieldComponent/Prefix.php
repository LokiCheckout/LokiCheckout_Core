<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;

abstract class Prefix extends AddressFieldComponent
{
    use OptionsBehaviour;

    protected array $validators = ['prefix'];

    public function getFieldLabel(): string
    {
        return 'Prefix';
    }
}
