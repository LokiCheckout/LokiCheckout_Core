<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Region as GenericRegionComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponentInterface;

class Region extends GenericRegionComponent implements FormTextSelectFieldInterface, ShippingAddressFieldComponentInterface
{
}
