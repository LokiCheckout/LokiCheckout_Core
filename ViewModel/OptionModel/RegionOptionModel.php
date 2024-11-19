<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\Region;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

class RegionOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
    }

    public function getOptions(Component $component): array
    {
        /** @var AddressFieldComponent $component */
        $countryId = $component->getAddressState()->load('country_id');
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
        $options[] = '';
        foreach ($regions as $region) {
            /** @var Region $region */
            $options[$region->getRegionId()] = $region->getName();
        }

        return $options;
    }
}
