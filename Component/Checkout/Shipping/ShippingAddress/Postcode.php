<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Postcode as GenericPostcodeComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class Postcode extends GenericPostcodeComponent implements ShippingAddressFieldInterface
{
}
