<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\MagewireCheckout\Magewire\Generic\EavFormFieldComponent;

class Firstname extends EavFormFieldComponent
{
    public function getAttributeCode(): string
    {
        return 'firstname';
    }
}
