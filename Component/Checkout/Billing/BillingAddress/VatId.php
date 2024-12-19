<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class VatId extends GenericVatIdComponent implements BillingAddressFieldInterface
{
}
