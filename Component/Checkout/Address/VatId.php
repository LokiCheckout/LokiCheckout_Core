<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Override;
use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\SetterInjection\InjectLokiCheckoutConfig;

class VatId extends AddressField
{
    use InjectLokiCheckoutConfig;

    protected array $filters = ['vat_id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->setVisibility();
    }

    private function setVisibility(): void
    {
        if (false === $this->lokiCheckoutConfig->enableVatIdOnlyForCompany()) {
            return;
        }

        $company = $this->addressState->load('company');
        if (!empty($company)) {
            return;
        }

        $this->allowRendering = false; // @todo: Double-check that this is still working
    }
}
