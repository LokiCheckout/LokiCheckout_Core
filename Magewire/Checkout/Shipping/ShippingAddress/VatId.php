<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class VatId extends GenericVatIdComponent implements ShippingAddressFieldComponentInterface
{
}
