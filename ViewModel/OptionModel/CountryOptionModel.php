<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class CountryOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        $countries = [];
        foreach ($this->directoryHelper->getCountryCollection() as $country) {
            $countries[$country->getCountryId()] = $country->getName();
        }

        asort($countries);

        return $countries;
    }
}
