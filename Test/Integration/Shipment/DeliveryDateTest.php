<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Shipment;

use LokiCheckout\Core\Shipment\DeliveryDate;
use LokiCheckout\Core\Shipment\DeliveryDate\Timeframe;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class DeliveryDateTest extends TestCase
{
    public function testGetIdFallsBackToTimestamp(): void
    {
        $deliveryDate = $this->createDeliveryDate('', 1700000000);

        $this->assertSame('1700000000', $deliveryDate->getId());
        $this->assertSame(1700000000, $deliveryDate->getTimestamp());
    }

    public function testGetIdUsesExplicitId(): void
    {
        $deliveryDate = $this->createDeliveryDate('monday', 1700000000);

        $this->assertSame('monday', $deliveryDate->getId());
    }

    public function testAddTimeframeAndSerialize(): void
    {
        $deliveryDate = $this->createDeliveryDate('monday', 1700000000);
        $this->assertSame([], $deliveryDate->getTimeframes());

        $timeframe = $this->createTimeframe();
        $deliveryDate->addTimeframe($timeframe);

        $this->assertCount(1, $deliveryDate->getTimeframes());

        $serialized = $deliveryDate->jsonSerialize();
        $this->assertSame('monday', $serialized['id']);
        $this->assertSame(1700000000, $serialized['timestamp']);
        $this->assertArrayHasKey('date', $serialized);
        $this->assertCount(1, $serialized['timeframes']);
    }

    private function createDeliveryDate(string $id, int $timestamp): DeliveryDate
    {
        return ObjectManager::getInstance()->create(DeliveryDate::class, [
            'id' => $id,
            'timestamp' => $timestamp,
        ]);
    }

    private function createTimeframe(): Timeframe
    {
        return ObjectManager::getInstance()->create(Timeframe::class, [
            'id' => 'tf1',
            'value' => ['from' => '09:00', 'to' => '17:00'],
            'label' => 'Morning',
            'fromTimestamp' => 1700000000,
            'toTimestamp' => 1700003600,
        ]);
    }
}
