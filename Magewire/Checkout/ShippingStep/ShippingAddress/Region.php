<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Override;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class Region extends ShippingAddressFieldComponent implements FormTextSelectFieldInterface
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    use OptionsBehaviour;

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
}
