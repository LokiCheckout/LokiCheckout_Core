<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Directory\Model\Region;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

class RegionOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        /** @var AddressFieldViewModel $viewModel */
        $addressState = $viewModel->getContext()->addressStateProvider->getAddressState($viewModel->getBlock());
        $countryId = $addressState->load('country_id');
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
