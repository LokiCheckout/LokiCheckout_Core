<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\AddressField;

use Yireo\LokiCheckout\Component\Form\Field\AddressField;

abstract class Telephone extends AddressField
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
