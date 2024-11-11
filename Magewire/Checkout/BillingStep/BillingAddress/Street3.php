<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Street3 as GenericStreetComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextMultipleFieldInterface;

class Street3 extends GenericStreetComponent implements BillingAddressFieldComponentInterface
{
}
