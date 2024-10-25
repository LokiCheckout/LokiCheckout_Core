<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Postcode as GenericPostcodeComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponentInterface;

class Postcode extends GenericPostcodeComponent implements ShippingAddressFieldComponentInterface
{
}
