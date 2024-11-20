<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Override;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectConfig;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectScopeConfig;

class Region extends AddressFieldComponent implements FormTextSelectFieldInterface
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    use OptionsBehaviour;
    use InjectScopeConfig;
    use InjectConfig;

    #[Override]
    public function boot(): void
    {
        $regionId = (string)$this->addressState->load('region_id');
        if (is_numeric($regionId) && $regionId > 0) {
            $this->value = $regionId;
            return;
        }

        $this->value = (string)$this->addressState->load('region');
    }

    public function updatedValue($value)
    {
        if (is_numeric($value)) {
            $this->addressState->save('region_id', $value);
        }

        $this->setValue($value);
        return $value;
    }

    public function isRequired(): bool
    {
        $countryId = $this->addressState->load('country_id');
        if (empty($countryId)) {
            return true;
        }

        $optionalCountries = $this->scopeConfig->getValue('general/country/optional_zip_countries');
        $optionalCountries = explode(',', $optionalCountries);
        if (in_array($countryId, $optionalCountries)) {
            return false;
        }

        return true;
    }

    public function isVisible(): bool
    {
        if (false === $this->config->hideRegionWhenOptional()) {
            return true;
        }

        if ($this->isRequired()) {
            return true;
        }

        return false;
    }
}
