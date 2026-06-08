<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\ShippingAmount;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class ShippingAmountTest extends TestCase
{
    private function getSubject(): ShippingAmount
    {
        return ObjectManager::getInstance()->get(ShippingAmount::class);
    }

    private function createTotals(): TotalsInterface
    {
        $totals = $this->createMock(TotalsInterface::class);
        $totals->method('getShippingAmount')->willReturn(5.0);
        $totals->method('getShippingInclTax')->willReturn(6.05);
        return $totals;
    }

    #[ConfigFixture('tax/cart_display/shipping', 2, 'store', 'default')]
    public function testReturnsInclTaxWhenConfigured(): void
    {
        $this->assertSame(6.05, $this->getSubject()->get($this->createTotals()));
    }

    #[ConfigFixture('tax/cart_display/shipping', 1, 'store', 'default')]
    public function testReturnsExclTaxWhenNotConfigured(): void
    {
        $this->assertSame(5.0, $this->getSubject()->get($this->createTotals()));
    }
}
