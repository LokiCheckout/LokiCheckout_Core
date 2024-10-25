<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Region as GenericRegionComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class Region extends GenericRegionComponent implements FormTextSelectFieldInterface, BillingAddressFieldComponentInterface
{
}
