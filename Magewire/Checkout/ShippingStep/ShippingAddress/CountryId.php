<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class CountryId extends ShippingAddressFormFieldComponent implements FormSelectFieldInterface
{
    use OptionsBehaviour;

    public function updatedValue($value)
    {
        $this->shippingAddressState->save('region', '');
        $this->shippingAddressState->save('region_id', '');
        return parent::updatedValue($value);
    }
}
