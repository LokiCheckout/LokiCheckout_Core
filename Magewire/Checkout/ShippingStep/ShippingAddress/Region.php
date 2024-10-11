<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Eav\Model\Config;
use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\FriggaCheckout\Util\Context;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Yireo\FriggaCheckout\ViewModel\SelectOptionsInterface;

class Region extends ShippingAddressFormFieldComponent implements FormTextSelectFieldInterface
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
        $regionId = (string)$this->checkoutState->getQuote()->getShippingAddress()->getRegionId();
        if (is_numeric($regionId) && $regionId > 0) {
            $this->value = $regionId;
            return;
        }

        $this->value = (string)$this->checkoutState->getQuote()->getShippingAddress()->getRegion();
    }

    public function updated($value, string $name)
    {
        if (is_numeric($value)) {
            $this->checkoutState->getQuote()->getShippingAddress()->setData('region_id', $value);
        }

        return parent::updated($value, $name);
    }
}
