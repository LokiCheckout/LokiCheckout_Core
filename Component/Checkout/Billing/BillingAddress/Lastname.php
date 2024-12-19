<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Lastname as GenericLastnameComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Lastname extends GenericLastnameComponent implements BillingAddressFieldInterface
{
}
