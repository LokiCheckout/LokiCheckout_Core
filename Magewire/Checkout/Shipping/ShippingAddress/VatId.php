<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent\VatId as GenericVatIdComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;

class VatId extends GenericVatIdComponent implements ShippingAddressFieldComponentInterface
{
    use InjectLokiCheckoutConfig;

    public function isVisible(): bool
    {
        if (false === $this->lokiCheckoutConfig->enableVatIdOnlyForCompany()) {
            return true;
        }

        $company = $this->addressState->load('company');
        if (empty($company)) {
            return false;
        }

        return true;
    }
}
