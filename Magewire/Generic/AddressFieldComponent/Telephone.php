<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class Telephone extends AddressFieldComponent
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
