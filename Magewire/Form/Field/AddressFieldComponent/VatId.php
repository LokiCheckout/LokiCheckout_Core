<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Override;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;

class VatId extends AddressFieldComponent
{
    use InjectLokiCheckoutConfig;

    protected array $filters = ['vat_id'];

    public function booted(): void
    {
        parent::booted();
        $this->addFieldRefreshListener('company');
        $this->addFieldRefreshListener('country_id');
    }

    #[Override]
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
