<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Region;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldViewModel;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;


class RegionViewModel extends AddressFieldViewModel
{
    use OptionsBehaviour;

    public function isAllowRendering(): bool
    {
        // @todo: This does not work properly at sequential requests
        if ($this->getContext()->moduleConfig->hideRegionWhenOptional() && false === $this->isRequired()) {
            $this->allowRendering = false;
        }

        return parent::isAllowRendering();
    }

    public function isRequired(): bool
    {
        $countryId = $this->getAddressState()->load('country_id');
        if (empty($countryId)) {
            return true;
        }

        $countriesWithStateRequired = $this->getContext()->scopeConfig->getValue('general/region/state_required');
        $countriesWithStateRequired = explode(',', $countriesWithStateRequired);
        if (in_array($countryId, $countriesWithStateRequired)) {
            return true;
        }

        return false;
    }
}
