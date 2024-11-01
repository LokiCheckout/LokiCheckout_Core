<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\Region as GenericRegionComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;

class Region extends GenericRegionComponent implements FormTextSelectFieldInterface, ShippingAddressFieldComponentInterface
{
    protected $listeners = [
        'shippingAddressCountryIdSave' => 'refresh'
    ];
}
