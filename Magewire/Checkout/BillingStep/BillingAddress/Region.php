<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Override;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class Region extends BillingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    use OptionsBehaviour;

    #[Override]

    public function boot(): void
    {
        $regionId = (string)$this->billingAddressState->load('region_id');
        if (is_numeric($regionId) && $regionId > 0) {
            $this->value = $regionId;
            return;
        }

        $this->value = (string)$regionId = $this->billingAddressState->load('region');
    }


    public function updatedValue($value)
    {
        if (is_numeric($value)) {
            $this->billingAddressState->save('region_id', $value);
        }

        return parent::updatedValue($value);
    }
}
