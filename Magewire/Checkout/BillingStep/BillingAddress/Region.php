<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Magento\Eav\Model\Config;
use Yireo\MagewireCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\MagewireCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\MagewireCheckout\Util\Context;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

class Region extends BillingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'region';

    protected $listeners = ['quoteSave' => 'refresh'];

    public function __construct(
        Context $context,
        private readonly CheckoutState $checkoutState,
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
        parent::__construct($context);
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

    public function getOptions(): array
    {
        $countryId = $this->checkoutState->getQuote()->getBillingAddress()->getCountryId();
        if (empty($countryId)) {
            return [];
        }

        $regionCollection = $this->regionCollectionFactory->create();
        $regionCollection->addFieldToFilter('country_id', ['eq' => $countryId]);
        $regions = $regionCollection->getItems();
        if (empty($regions)) {
            return [];
        }

        $options = [];
        foreach ($regions as $region) {
            /** @var Region $region */
            $options[$region->getRegionId()] = $region->getName();
        }

        return $options;
    }
}
