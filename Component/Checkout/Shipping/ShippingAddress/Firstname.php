<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Firstname as GenericFirstnameComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class Firstname extends GenericFirstnameComponent implements ShippingAddressFieldInterface
{
}
