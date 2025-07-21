<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Region;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;

class RegionViewModel extends AddressFieldViewModel
{
    public function isAllowRendering(): bool
    {
        // @todo: This does not work properly at sequential requests
        if ($this->getContext()->getModuleConfig()->hideRegionWhenOptional() && false === $this->isRequired()) {
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

        $countriesWithStateRequired = (string)$this->getContext()->getScopeConfig()->getValue('general/region/state_required');
        if (empty($countriesWithStateRequired)) {
            return false;
        }

        $countriesWithStateRequired = explode(',', $countriesWithStateRequired);
        return in_array($countryId, $countriesWithStateRequired);
    }
}
