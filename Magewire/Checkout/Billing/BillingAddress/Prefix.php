<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Prefix as GenericPrefixComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;

class Prefix extends GenericPrefixComponent implements BillingAddressFieldComponentInterface
{
}
