<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Street as GenericStreetComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class Street extends GenericStreetComponent implements FormTextMultipleFieldInterface, BillingAddressFieldComponentInterface
{
}
