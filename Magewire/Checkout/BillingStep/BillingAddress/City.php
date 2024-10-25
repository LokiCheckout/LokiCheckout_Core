<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\City as GenericCityComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class City extends GenericCityComponent implements BillingAddressFieldComponentInterface
{
}
