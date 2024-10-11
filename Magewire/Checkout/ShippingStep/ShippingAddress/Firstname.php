<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class Firstname extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'firstname';

    protected $rules = [
        'value' => 'required|min:3',
    ];

    public function isRequired(): bool
    {
        return true;
    }
}
