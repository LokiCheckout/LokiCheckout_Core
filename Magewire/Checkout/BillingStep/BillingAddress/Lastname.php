<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Magento\Framework\Phrase;
use Yireo\MagewireCheckout\Magewire\Generic\BillingAddressFormFieldComponent;

class Lastname extends BillingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'lastname';
}
