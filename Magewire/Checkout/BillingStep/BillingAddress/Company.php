<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\Company as GenericCompanyComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;
class Company extends GenericCompanyComponent implements BillingAddressFieldComponentInterface
{
}
