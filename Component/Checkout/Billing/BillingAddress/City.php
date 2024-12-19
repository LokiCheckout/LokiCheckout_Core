<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\City as GenericCityComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class City extends GenericCityComponent implements BillingAddressFieldInterface
{
}
