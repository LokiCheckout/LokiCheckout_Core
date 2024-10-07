<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class City extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'city';

    public function isRequired(): bool
    {
        return true;
    }
}
