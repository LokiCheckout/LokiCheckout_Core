<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Framework\Phrase;
use Yireo\MagewireCheckout\Magewire\Generic\EavFormFieldComponent;

class Suffix extends EavFormFieldComponent
{
    public function getAttributeCode(): string
    {
        return 'suffix';
    }
}
