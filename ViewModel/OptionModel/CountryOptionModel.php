<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ViewModel\ViewModelInterface;


class CountryOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
    ) {
    }

    public function getOptions(ViewModelInterface $viewModel): array
    {
        $countries = [];
        foreach ($this->directoryHelper->getCountryCollection() as $country) {
            $countries[$country->getCountryId()] = $country->getName();
        }

        asort($countries);

        return $countries;
    }
}
