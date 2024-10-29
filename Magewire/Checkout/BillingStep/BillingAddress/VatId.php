<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponentInterface;

class VatId extends GenericVatIdComponent implements BillingAddressFieldComponentInterface
{

    protected $listeners = [
        'billingAddressCompanySave' => 'refresh'
    ];
}
