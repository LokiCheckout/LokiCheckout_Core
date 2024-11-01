<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\CountryId as GenericCountryIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class CountryId extends GenericCountryIdComponent implements FormSelectFieldInterface, ShippingAddressFieldComponentInterface
{
}
