<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\City as GenericCityComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponentInterface;

class City extends GenericCityComponent implements ShippingAddressFieldComponentInterface
{
}
