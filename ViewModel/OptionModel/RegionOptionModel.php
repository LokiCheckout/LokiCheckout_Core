<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Magento\Directory\Model\Region;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class RegionOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        /** @var AddressFieldViewModel $viewModel */
        $addressState = $viewModel->getContext()->getAddressStateProvider()->getAddressState($viewModel->getBlock());
        $countryId = $addressState->get('country_id');
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
