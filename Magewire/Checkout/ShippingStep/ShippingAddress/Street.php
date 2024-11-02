<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Street as GenericStreetComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextMultipleFieldInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class Street extends GenericStreetComponent implements FormTextMultipleFieldInterface, ShippingAddressFieldComponentInterface
{
}
