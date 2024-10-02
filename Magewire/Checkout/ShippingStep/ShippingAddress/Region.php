<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Eav\Model\Config;
use Yireo\MagewireCheckout\Magewire\Generic\EavFormFieldComponent;
use Yireo\MagewireCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

class Region extends EavFormFieldComponent implements FormTextSelectFieldInterface
{
    protected $listeners = ['quoteSave' => 'refresh'];

    public function __construct(
        Config $eavConfig,
        CheckoutState $checkoutState,
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
        parent::__construct($eavConfig, $checkoutState);
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

    public function getAttributeCode(): string
    {
        return 'region';
    }

    public function updated($value, string $name)
    {
        if (is_numeric($value)) {
            $this->checkoutState->getQuote()->getShippingAddress()->setData('region_id', $value);
        }

        return parent::updated($value, $name);
    }

    public function getOptions(): array
    {
        $countryId = $this->checkoutState->getQuote()->getShippingAddress()->getCountryId();
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
