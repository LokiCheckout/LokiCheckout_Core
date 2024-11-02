<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Prefix as GenericPrefixComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextSelectFieldInterface;

class Prefix extends GenericPrefixComponent implements FormTextSelectFieldInterface, BillingAddressFieldComponentInterface
{
}
