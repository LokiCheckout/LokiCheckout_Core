<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Telephone as GenericTelephoneComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Telephone extends GenericTelephoneComponent implements BillingAddressFieldInterface
{
}
