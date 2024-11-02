<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Email as GenericEmailComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class Email extends GenericEmailComponent implements ShippingAddressFieldComponentInterface
{
}
