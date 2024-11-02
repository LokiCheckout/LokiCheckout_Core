<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Company as GenericCompanyComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class Company extends GenericCompanyComponent implements ShippingAddressFieldComponentInterface
{
}
