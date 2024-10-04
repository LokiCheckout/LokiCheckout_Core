<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Source\Options;

use Magento\Directory\Helper\Data as DirectoryHelper;

class CountryOptions
{
    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
    ) {
    }

    public function getOptions(): array
    {
        $countries = [];
        foreach ($this->directoryHelper->getCountryCollection() as $country) {
            $countries[$country->getCountryId()] = $country->getName();
        }

        asort($countries);

        return $countries;
    }
}
