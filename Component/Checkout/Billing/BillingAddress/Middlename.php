<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Middlename as GenericMiddlenameComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Middlename extends GenericMiddlenameComponent implements BillingAddressFieldInterface
{
}
