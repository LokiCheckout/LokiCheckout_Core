<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Middlename as GenericMiddlenameComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class Middlename extends GenericMiddlenameComponent implements ShippingAddressFieldComponentInterface
{
}
