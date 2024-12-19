<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Street1 as GenericStreetComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Street1 extends GenericStreetComponent implements BillingAddressFieldInterface
{
}
