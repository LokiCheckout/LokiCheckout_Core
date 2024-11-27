<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\CountryId as GenericCountryIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class CountryId extends GenericCountryIdComponent implements ShippingAddressFieldComponentInterface
{
}
