<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Prefix as GenericPrefixComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponentInterface;

class Prefix extends GenericPrefixComponent implements FormTextSelectFieldInterface, ShippingAddressFieldComponentInterface
{
}
