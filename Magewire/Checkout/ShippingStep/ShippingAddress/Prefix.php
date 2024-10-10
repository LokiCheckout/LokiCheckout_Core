<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\FriggaCheckout\Source\Options\PrefixOptions;
use Yireo\FriggaCheckout\Util\Context;

class Prefix extends ShippingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    protected $rules = [
        'value' => 'required|min:6',
    ];

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
