<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

class Telephone extends AddressFieldComponent
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
