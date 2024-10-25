<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Override;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class Region extends AddressFieldComponent implements FormTextSelectFieldInterface
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
