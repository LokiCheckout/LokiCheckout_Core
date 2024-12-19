<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Fax as GenericFaxComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Fax extends GenericFaxComponent implements BillingAddressFieldInterface
{
}
