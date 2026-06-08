<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Repository;

use LokiCheckout\Core\Repository\RegionRepository;
use LokiCheckout\Core\Util\RegionProvider;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class RegionRepositoryTest extends TestCase
{
    private RegionRepository $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(RegionRepository::class);
    }

    public function testGetByIdForKnownRegion(): void
    {
        $regionProvider = ObjectManager::getInstance()->get(RegionProvider::class);
        $regionId = $regionProvider->getRegionId('US', 'California');
        $this->assertGreaterThan(0, $regionId);

        $region = $this->subject->getById($regionId);

        $this->assertSame($regionId, (int)$region->getId());
        $this->assertSame('California', $region->getName());
        $this->assertSame('US', $region->getCountryId());
    }

    public function testGetByIdForUnknownRegion(): void
    {
        $region = $this->subject->getById(9999999);

        $this->assertNull($region->getId());
    }
}
