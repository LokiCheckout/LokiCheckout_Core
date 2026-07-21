<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Loki\Field\Util\Option;
use Loki\Field\Util\OptionFactory;
use Loki\Field\Util\OptionGroupFactory;
use Loki\Field\Util\OptionGroupModelInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Loki\Components\Component\ComponentViewModelInterface;

class CountryOptionModel implements ArgumentInterface, OptionGroupModelInterface
{
    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
        private OptionGroupFactory $optionGroupFactory,
        private OptionFactory $optionFactory,
        private readonly array $skipCountries = []
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        $topCountryCodes = $this->directoryHelper->getTopCountryCodes();

        static $options = false;
        if (is_array($options)) {
            return $options;
        }

        $options = [];
        foreach ($this->directoryHelper->getCountryCollection() as $country) {
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

        if (!empty($topCountryCodes)) {
            $optionGroups = [];
            $topCountries = array_filter($options, function(Option $option) use ($topCountryCodes) {
                return in_array($option->getValue(), $topCountryCodes);
            });

            $otherCountries = array_filter($options, function(Option $option) use ($topCountryCodes) {
                return false === in_array($option->getValue(), $topCountryCodes);
            });

            if (!empty($topCountries) && !empty($otherCountries)) {
                $optionGroups[] = $this->optionGroupFactory->create('Top Countries', $topCountries);
                $optionGroups[] = $this->optionGroupFactory->create('Other Countries', $otherCountries);

                $options = $optionGroups;
                return $options;
            }
        }

        return $options;
    }
}
