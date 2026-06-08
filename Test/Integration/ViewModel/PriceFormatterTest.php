<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\PriceFormatter;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class PriceFormatterTest extends TestCase
{
    private PriceFormatter $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(PriceFormatter::class);
    }

    public function testFormatReturnsFormattedPrice(): void
    {
        $formatted = $this->subject->format(12.5);

        $this->assertIsString($formatted);
        $this->assertStringContainsString('12', $formatted);
    }

    public function testDisplayFlagsAreBooleans(): void
    {
        $this->assertIsBool($this->subject->displayCartPricesInclTax());
        $this->assertIsBool($this->subject->displayCartPricesExclTax());
        $this->assertIsBool($this->subject->displayCartPricesBoth());
    }
}
