<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Loki\Field\Util\Option;
use Loki\Field\Util\OptionFactory;
use Loki\Field\Util\OptionGroupFactory;
use Loki\Field\Util\OptionGroupModelInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Loki\Components\Component\ComponentViewModelInterface;

class CountryOptionModel implements ArgumentInterface, OptionGroupModelInterface
{
    private array $options = [];

    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
        private OptionGroupFactory $optionGroupFactory,
        private OptionFactory $optionFactory,
        private readonly CountryCollectionFactory $countryCollectionFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly array $skipCountries = []
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        if (!isset($this->options[$storeId])) {
            $this->options[$storeId] = $this->buildOptions($storeId);
        }

        return $this->options[$storeId];
    }

    private function buildOptions(int $storeId): array
    {
        $options = [];
        foreach ($this->countryCollectionFactory->create()->loadByStore($storeId) as $country) {
            if (empty($country->getCountryId())) {
                continue;
            }

            if (empty($country->getName())) {
                continue;
            }

            if (in_array($country->getCountryId(), $this->skipCountries)) {
                continue;
            }

            $options[] = $this->optionFactory->create(
                $country->getCountryId(),
                $country->getName()
            );
        }

        uasort($options, function(Option $a, Option $b) {
            return strcmp($a->getLabel(), $b->getLabel());
        });

        $topCountryCodes = $this->directoryHelper->getTopCountryCodes();
        if (empty($topCountryCodes)) {
            return $options;
        }

        $topCountries = array_filter($options, function(Option $option) use ($topCountryCodes) {
            return in_array($option->getValue(), $topCountryCodes);
        });

        $otherCountries = array_filter($options, function(Option $option) use ($topCountryCodes) {
            return false === in_array($option->getValue(), $topCountryCodes);
        });

        if (empty($topCountries) || empty($otherCountries)) {
            return $options;
        }

        return [
            $this->optionGroupFactory->create('Top Countries', $topCountries),
            $this->optionGroupFactory->create('Other Countries', $otherCountries),
        ];
    }
}
