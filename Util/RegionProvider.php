<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

class RegionProvider
{
    public function __construct(
        private readonly RegionCollectionFactory $regionCollectionFactory,
    ) {
    }

    public function getRegionId(string $countryId, string $regionName): int
    {
        $regionCollection = $this->regionCollectionFactory->create();
        $regionCollection->addFieldToFilter('country_id', ['eq' => $countryId]);
        $regionCollection->addFieldToFilter('default_name', ['eq' => $regionName]);
        $regionId = (int)$regionCollection->getFirstItem()->getId();

        return $regionId;
    }

    public function saveRegionToAddress(AddressInterface $address, string $regionName)
    {
        $regionId = $this->getRegionId($address->getCountryId(), $regionName);
        if ($regionId > 0) {
            $address->setRegionId($this->getRegionId($address->getCountryId(), $regionName));
        }

        $address->setRegionCode($regionName);
        $address->setRegion($regionName);
    }
}
