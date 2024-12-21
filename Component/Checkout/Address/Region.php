<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Override;
use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Component\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\Component\SetterInjection\InjectScopeConfig;

class Region extends AddressField
{
    use OptionsBehaviour;
    use InjectScopeConfig;
    use InjectLokiCheckoutConfig;

    #[Override]
    public function initialize(): void
    {
        $regionId = (string)$this->addressState->load('region_id');
        if (is_numeric($regionId) && $regionId > 0) {
            $this->value = $regionId;
            return;
        }

        $this->value = (string)$this->addressState->load('region');

        // @todo: This does not work properly at sequential requests
        if ($this->lokiCheckoutConfig->hideRegionWhenOptional() && false === $this->isRequired()) {
            $this->allowRendering = false;
        }

        parent::initialize();
    }

    public function isRequired(): bool
    {
        $countryId = $this->addressState->load('country_id');
        if (empty($countryId)) {
            return true;
        }

        $countriesWithStateRequired = $this->scopeConfig->getValue('general/region/state_required');
        $countriesWithStateRequired = explode(',', $countriesWithStateRequired);
        if (in_array($countryId, $countriesWithStateRequired)) {
            return true;
        }

        return false;
    }
}
