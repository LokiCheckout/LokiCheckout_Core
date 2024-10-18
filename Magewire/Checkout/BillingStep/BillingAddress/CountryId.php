<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;

class CountryId extends BillingAddressFieldComponent implements FormSelectFieldInterface
{
    use OptionsBehaviour;

    public function updatedValue($value)
    {
        $this->billingAddressState->save('region', '');
        $this->billingAddressState->save('region_id', '');

        return parent::updatedValue($value);
    }
}
