<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Prefix as GenericPrefixComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class Prefix extends GenericPrefixComponent implements FormTextSelectFieldInterface, BillingAddressFieldComponentInterface
{
}
