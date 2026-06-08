<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Util\RegionProvider;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class RegionProviderTest extends TestCase
{
    private RegionProvider $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(RegionProvider::class);
    }

    public function testGetRegionIdForKnownRegion(): void
    {
        $regionId = $this->subject->getRegionId('US', 'California');
        $this->assertGreaterThan(0, $regionId);
    }

    public function testGetRegionIdForUnknownRegion(): void
    {
        $regionId = $this->subject->getRegionId('US', 'Nonexistent Region');
        $this->assertSame(0, $regionId);
    }

    public function testSaveRegionToAddress(): void
    {
        $addressFactory = ObjectManager::getInstance()->get(AddressFactory::class);
        $address = $addressFactory->create();
        $address->setCountryId('US');

        $this->subject->saveRegionToAddress($address, 'California');

        $this->assertGreaterThan(0, (int)$address->getRegionId());
        $this->assertSame('California', $address->getData('region_code'));
        $this->assertSame('California', $address->getData('region'));
    }
}
