<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;
use Yireo\LokiCheckout\Mutator\Form\Field\AddressField\Firstname as GenericMutator;

class Firstname extends GenericMutator implements ShippingAddressFieldInterface
{
}
