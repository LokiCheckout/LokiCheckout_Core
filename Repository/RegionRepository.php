<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Repository;

use Magento\Directory\Model\Region;
use Magento\Directory\Model\RegionFactory;
use Magento\Directory\Model\ResourceModel\Region as RegionResourceModel;

class RegionRepository
{
    public function __construct(
        private RegionFactory $regionFactory,
        private RegionResourceModel $regionResourceModel
    ){
    }

    public function getById(int $regionId): Region
    {
        $region = $this->regionFactory->create();
        $this->regionResourceModel->load($region, $regionId);
        return $region;
    }
}
