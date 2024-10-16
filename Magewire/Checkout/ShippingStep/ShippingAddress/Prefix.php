<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class Prefix extends ShippingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    use OptionsBehaviour;
}
