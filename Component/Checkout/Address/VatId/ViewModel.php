<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\VatId;

use Override;
use Yireo\LokiCheckout\Component\Form\Field\AddressFieldViewModelViewModel;
use Yireo\LokiCheckout\Component\SetterInjection\InjectLokiCheckoutConfig;

class ViewModel extends AddressFieldViewModelViewModel
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
