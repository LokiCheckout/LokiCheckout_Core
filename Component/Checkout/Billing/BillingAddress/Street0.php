<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Street0 as GenericStreetComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;
use Yireo\LokiCheckout\Component\Form\Field\FormTextMultipleFieldInterface;

class Street0 extends GenericStreetComponent implements BillingAddressFieldInterface
{
}
