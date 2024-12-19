<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\AddressField;

use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

abstract class Street2 extends StreetLine
{
    use OptionsBehaviour;

    protected int $index = 2;
}
