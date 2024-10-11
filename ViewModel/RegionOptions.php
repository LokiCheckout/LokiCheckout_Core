<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\ViewModel;

use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magewirephp\Magewire\Component;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Region;

class RegionOptions implements ArgumentInterface, SelectOptionsInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
    }

    public function getOptions(Component $component): array
    {
        // @todo: Switch between shipping and billing
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
