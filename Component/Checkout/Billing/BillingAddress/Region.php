<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Region as GenericRegionComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Region extends GenericRegionComponent implements BillingAddressFieldInterface
{
}
