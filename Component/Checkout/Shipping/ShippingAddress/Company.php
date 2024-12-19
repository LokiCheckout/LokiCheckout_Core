<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Company as GenericCompanyComponent;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;

class Company extends GenericCompanyComponent implements ShippingAddressFieldInterface
{
}
