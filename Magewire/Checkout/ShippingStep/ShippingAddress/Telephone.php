<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Telephone as GenericTelephoneComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponentInterface;

class Telephone extends GenericTelephoneComponent implements ShippingAddressFieldComponentInterface
{
}
