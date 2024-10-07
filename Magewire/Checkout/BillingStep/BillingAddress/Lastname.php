<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Magento\Framework\Phrase;
use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;

class Lastname extends BillingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'lastname';

    public function isRequired(): bool
    {
        return true;
    }
}
