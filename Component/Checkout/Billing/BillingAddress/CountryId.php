<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\CountryId as GenericCountryIdComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class CountryId extends GenericCountryIdComponent implements BillingAddressFieldInterface
{
}
