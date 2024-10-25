<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Lastname as GenericLastnameComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class Lastname extends GenericLastnameComponent implements BillingAddressFieldComponentInterface
{
}
