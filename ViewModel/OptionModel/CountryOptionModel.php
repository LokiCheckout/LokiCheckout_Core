<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\ViewModel\OptionModel;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magewirephp\Magewire\Component;

class CountryOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
    ) {
    }

    public function getOptions(Component $component): array
    {
        $countries = [];
        foreach ($this->directoryHelper->getCountryCollection() as $country) {
            $countries[$country->getCountryId()] = $country->getName();
        }

        asort($countries);

        return $countries;
    }
}
