<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Shipment\DeliveryDate;

use LokiCheckout\Core\Shipment\DeliveryDate\Timeframe;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class TimeframeTest extends TestCase
{
    private function createTimeframe(array $data = []): Timeframe
    {
        return ObjectManager::getInstance()->create(Timeframe::class, array_merge([
            'id' => 'tf1',
            'value' => ['from' => '09:00', 'to' => '17:00'],
            'label' => 'Morning slot',
            'fromTimestamp' => 1700000000,
            'toTimestamp' => 1700003600,
            'price' => 4.95,
            'disabled' => false,
            'carrier' => 'flatrate',
            'carrierImage' => 'flatrate.png',
        ], $data));
    }

    public function testGetters(): void
    {
        $timeframe = $this->createTimeframe();

        $this->assertSame('tf1', $timeframe->getId());
        $this->assertSame('Morning slot', $timeframe->getLabel());
        $this->assertSame(4.95, $timeframe->getPrice());
        $this->assertFalse($timeframe->isDisabled());
        $this->assertSame('flatrate', $timeframe->getCarrier());
        $this->assertSame('flatrate.png', $timeframe->getCarrierImage());
        $this->assertSame(['from' => '09:00', 'to' => '17:00'], $timeframe->getValue());
        $this->assertSame(1700000000, $timeframe->getFromTimestamp());
        $this->assertSame(1700003600, $timeframe->getToTimestamp());
    }

    public function testDateFormatting(): void
    {
        $timeframe = $this->createTimeframe();

        $this->assertSame(date('Y-m-d', 1700000000), $timeframe->getFromDate());
        $this->assertSame(date('Y-m-d', 1700003600), $timeframe->getToDate());
        $this->assertSame(date('H:i', 1700000000), $timeframe->getFromTime());
        $this->assertSame(date('H:i', 1700003600), $timeframe->getToTime());
    }

    public function testSerializeShape(): void
    {
        $timeframe = $this->createTimeframe();
        $serialized = $timeframe->jsonSerialize();

        foreach (['id', 'label', 'value', 'price', 'fromDay', 'fromTime', 'toDay', 'toTime', 'disabled', 'carrier', 'carrierImage'] as $key) {
            $this->assertArrayHasKey($key, $serialized);
        }
    }

    public function testDisabledFlag(): void
    {
        $timeframe = $this->createTimeframe(['disabled' => true]);

        $this->assertTrue($timeframe->isDisabled());
    }
}
