<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Framework\Phrase;
use Yireo\MagewireCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class Lastname extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'lastname';

    public function isRequired(): bool
    {
        return true;
    }
}
