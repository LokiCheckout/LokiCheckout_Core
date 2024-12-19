<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\City as GenericCityComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class City extends GenericCityComponent implements ShippingAddressFieldInterface
{
}
