<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\FriggaCheckout\Source\Options\PrefixOptions;
use Yireo\FriggaCheckout\Util\Context;

class Prefix extends BillingAddressFormFieldComponent implements FormTextSelectFieldInterface
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
