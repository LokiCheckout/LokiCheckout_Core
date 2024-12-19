<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\CountryId as GenericCountryIdComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class CountryId extends GenericCountryIdComponent implements ShippingAddressFieldInterface
{
}
