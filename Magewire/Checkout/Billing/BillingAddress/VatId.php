<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;

class VatId extends GenericVatIdComponent implements BillingAddressFieldComponentInterface
{
    protected $listeners = [
        'billingAddressCompanySave' => 'refresh'
    ];
}
