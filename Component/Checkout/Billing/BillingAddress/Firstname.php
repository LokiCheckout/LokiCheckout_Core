<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Firstname as GenericFirstnameComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Firstname extends GenericFirstnameComponent implements BillingAddressFieldInterface
{
}
