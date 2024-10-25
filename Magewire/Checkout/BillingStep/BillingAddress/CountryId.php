<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\CountryId as GenericCountryIdComponent;

class CountryId extends GenericCountryIdComponent implements FormSelectFieldInterface, BillingAddressFieldComponentInterface
{
}
