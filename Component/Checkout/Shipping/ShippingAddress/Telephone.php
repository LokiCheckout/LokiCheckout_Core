<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Telephone as GenericTelephoneComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class Telephone extends GenericTelephoneComponent implements ShippingAddressFieldInterface
{
}
