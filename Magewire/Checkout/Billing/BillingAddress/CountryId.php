<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\CountryId as GenericCountryIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;

class CountryId extends GenericCountryIdComponent implements BillingAddressFieldComponentInterface
{
}
