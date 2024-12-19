<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class VatId extends GenericVatIdComponent implements ShippingAddressFieldInterface
{
}
