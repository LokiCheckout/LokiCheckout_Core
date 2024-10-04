<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\MagewireCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\MagewireCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\MagewireCheckout\Source\Options\PrefixOptions;
use Yireo\MagewireCheckout\Util\Context;

class Prefix extends ShippingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'prefix';

    public function __construct(
        Context $context,
        private PrefixOptions $prefixOptions,
    ) {
        parent::__construct($context);
    }

    public function getOptions(): array
    {
        return $this->prefixOptions->getOptions();
    }
}
