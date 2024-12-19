<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Prefix as GenericPrefixComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class Prefix extends GenericPrefixComponent implements ShippingAddressFieldInterface
{
}
