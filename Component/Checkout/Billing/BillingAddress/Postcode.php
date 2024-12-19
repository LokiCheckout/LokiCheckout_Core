<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Postcode as GenericPostcodeComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Postcode extends GenericPostcodeComponent implements BillingAddressFieldInterface
{
}
