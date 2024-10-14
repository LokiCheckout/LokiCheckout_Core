<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Override;
use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class Region extends ShippingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    use OptionsBehaviour;

    #[Override]
    public function boot(): void
    {
        $regionId = (string)$this->shippingAddressState->load('region_id');
        if (is_numeric($regionId) && $regionId > 0) {
            $this->value = $regionId;
            return;
        }

        $this->value = (string)$this->shippingAddressState->load('region');
    }

    public function updatedValue($value)
    {
        if (is_numeric($value)) {
            $this->shippingAddressState->save('region_id', $value);
        }

        return parent::updatedValue($value);
    }
}
