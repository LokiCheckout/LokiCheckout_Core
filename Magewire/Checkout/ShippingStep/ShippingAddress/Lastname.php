<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Framework\Phrase;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class Lastname extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'lastname';

    protected $rules = [
        'value' => 'required|min:3',
    ];

    public function isRequired(): bool
    {
        return true;
    }
}
