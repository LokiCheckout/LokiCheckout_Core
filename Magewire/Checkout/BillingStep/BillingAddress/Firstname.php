<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Firstname as GenericFirstnameComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class Firstname extends GenericFirstnameComponent implements BillingAddressFieldComponentInterface
{
}
