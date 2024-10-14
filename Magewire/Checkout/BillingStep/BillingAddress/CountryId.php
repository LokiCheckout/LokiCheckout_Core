<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;

class CountryId extends BillingAddressFormFieldComponent implements FormSelectFieldInterface
{
    use OptionsBehaviour;

    public function updatedValue($value)
    {
        $this->billingAddressState->save('region', '');
        $this->billingAddressState->save('region_id', '');

        return parent::updatedValue($value);
    }
}
