<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Middlename as GenericMiddlenameComponent;

class Middlename extends GenericMiddlenameComponent implements BillingAddressFieldComponentInterface
{
}
