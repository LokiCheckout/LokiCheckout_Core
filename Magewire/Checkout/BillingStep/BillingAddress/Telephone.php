<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Telephone as GenericTelephoneComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;

class Telephone extends GenericTelephoneComponent implements BillingAddressFieldComponentInterface
{
}
