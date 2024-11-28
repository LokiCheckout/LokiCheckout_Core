<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Postcode as GenericPostcodeComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;

class Postcode extends GenericPostcodeComponent implements BillingAddressFieldComponentInterface
{
}
