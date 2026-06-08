<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\TaxTotal;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Cart\TotalSegment;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class TaxTotalTest extends TestCase
{
    private function getSubject(): TaxTotal
    {
        return ObjectManager::getInstance()->get(TaxTotal::class);
    }

    private function createTotalSegment(float $value): TotalSegment
    {
        $segment = ObjectManager::getInstance()->create(TotalSegment::class);
        $segment->setValue($value);
        return $segment;
    }

    #[ConfigFixture('tax/cart_display/full_summary', 1, 'store', 'default')]
    public function testShowFullSummaryWhenValuePositiveAndEnabled(): void
    {
        $this->assertTrue($this->getSubject()->showFullSummary($this->createTotalSegment(5.0)));
    }

    #[ConfigFixture('tax/cart_display/full_summary', 1, 'store', 'default')]
    public function testHideFullSummaryWhenValueZero(): void
    {
        $this->assertFalse($this->getSubject()->showFullSummary($this->createTotalSegment(0.0)));
    }

    #[ConfigFixture('tax/cart_display/full_summary', 0, 'store', 'default')]
    public function testHideFullSummaryWhenDisabled(): void
    {
        $this->assertFalse($this->getSubject()->showFullSummary($this->createTotalSegment(5.0)));
    }

    public function testGetRatesFromTotalEmptyWhenNoExtensionAttributes(): void
    {
        $this->assertSame([], $this->getSubject()->getRatesFromTotal($this->createTotalSegment(5.0)));
    }
}
