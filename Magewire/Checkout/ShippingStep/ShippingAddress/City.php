<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\MagewireCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class City extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'city';

    public function isRequired(): bool
    {
        return true;
    }
}
