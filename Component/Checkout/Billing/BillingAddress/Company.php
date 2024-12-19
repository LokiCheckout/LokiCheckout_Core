<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Form\Field\AddressField\Company as GenericCompanyComponent;
use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;

class Company extends GenericCompanyComponent implements BillingAddressFieldInterface
{
}
