<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;

abstract class Street2 extends StreetLine
{
    use OptionsBehaviour;

    protected int $index = 2;
}
