<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Suffix as GenericSuffixComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Suffix extends GenericSuffixComponent implements BillingAddressFieldInterface
{
}
