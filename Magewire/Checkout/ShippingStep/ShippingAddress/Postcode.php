<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Postcode as GenericPostcodeComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class Postcode extends GenericPostcodeComponent implements ShippingAddressFieldComponentInterface
{
    protected $listeners = [
        'shippingAddressCountryIdSave' => 'validateComponent'
    ];
}
