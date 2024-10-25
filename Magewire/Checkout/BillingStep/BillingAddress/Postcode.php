<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Postcode as GenericPostcodeComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class Postcode extends GenericPostcodeComponent implements BillingAddressFieldComponentInterface
{
}
