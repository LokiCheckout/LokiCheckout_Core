<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Override;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;

class VatId extends AddressFieldComponent
{
    use InjectLokiCheckoutConfig;

    protected array $filters = ['vat_id'];

    public function boot(): void
    {
        parent::boot();
        $this->addFieldRefreshListener('company');
        $this->addFieldRefreshListener('country_id');

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

        $this->visible = false; // @todo: Double-check that this is still working
    }
}
