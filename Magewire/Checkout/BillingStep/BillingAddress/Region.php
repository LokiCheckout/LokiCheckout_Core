<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\FriggaCheckout\Util\Context;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class Region extends BillingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'region';

    protected $listeners = ['quoteSave' => 'refresh'];

    use OptionsBehaviour;

    public function __construct(
        Validator $validator,
        Context $context,
        private readonly CheckoutState $checkoutState,
    ) {
        parent::__construct($validator, $context);
    }

    public function boot(): void
    {
        $regionId = (string)$this->checkoutState->getQuote()->getBillingAddress()->getRegionId();
        if (is_numeric($regionId) && $regionId > 0) {
            $this->value = $regionId;
            return;
        }

        $this->value = (string)$this->checkoutState->getQuote()->getBillingAddress()->getRegion();
    }

    public function updated($value, string $name)
    {
        if (is_numeric($value)) {
            $this->checkoutState->getQuote()->getBillingAddress()->setData('region_id', $value);
        }

        return parent::updated($value, $name);
    }
}
