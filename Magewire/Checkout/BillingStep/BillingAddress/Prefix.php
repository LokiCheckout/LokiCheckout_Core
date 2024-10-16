<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class Prefix extends BillingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    use OptionsBehaviour;
}
