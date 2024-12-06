<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

abstract class Fax extends AddressFieldComponent
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
