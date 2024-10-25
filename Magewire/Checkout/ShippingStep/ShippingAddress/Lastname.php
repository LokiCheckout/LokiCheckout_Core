<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Lastname as GenericLastnameComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponentInterface;

class Lastname extends GenericLastnameComponent implements ShippingAddressFieldComponentInterface
{
}
