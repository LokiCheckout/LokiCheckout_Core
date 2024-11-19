<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectConfig;

class VatId extends GenericVatIdComponent implements ShippingAddressFieldComponentInterface
{
    use InjectConfig;

    protected $listeners = [
        'shippingAddressCompanySave' => 'refresh'
    ];

    public function isVisible(): bool
    {
        if (false === $this->config->enableVatIdOnlyForCompany()) {
            return true;
        }

        $company = $this->addressState->load('company');
        if (empty($company)) {
            return false;
        }

        return true;
    }
}
