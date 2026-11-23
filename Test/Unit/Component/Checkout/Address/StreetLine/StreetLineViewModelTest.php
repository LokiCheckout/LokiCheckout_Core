<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Component\Checkout\Address\StreetLine;

use LokiCheckout\Core\Component\Checkout\Address\StreetLine\StreetLineViewModel;
use Magento\Framework\View\Element\Template;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StreetLineViewModelTest extends TestCase
{
    #[DataProvider('getLabelCases')]
    public function testFieldLabel(
        string $fieldName,
        string $layoutLabel,
        string $expectedLabel,
        bool $expectedShowLabel
    ): void {
        $viewModel = new StreetLineViewModel();
        $viewModel->setBlock($this->getBlock($fieldName, $layoutLabel));

        $this->assertSame($expectedLabel, $viewModel->getFieldLabel());
        $this->assertSame($expectedShowLabel, $viewModel->showFieldLabel());
    }

    public static function getLabelCases(): array
    {
        return [
            ['street0', 'Street', 'Street', true],
            ['street0', '', 'Street0', true],
            ['street0', '-', 'Street Address Line 1', true],
            ['street1', '-', 'Street Address Line 2', false],
            ['street3', '-', 'Street Address Line 4', false],
            ['street1', '', 'Street1', true],
            ['street1', 'Housenumber', 'Housenumber', true],
        ];
    }

    private function getBlock(string $fieldName, string $layoutLabel): Template
    {
        $block = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getNameInLayout'])
            ->getMock();
        $block->method('getNameInLayout')->willReturn('loki-checkout.shipping.address.' . $fieldName);
        $block->setData('field_label', $layoutLabel);

        return $block;
    }
}
