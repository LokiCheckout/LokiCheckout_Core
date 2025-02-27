<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\ViewModel;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\StepForwardButtonLabel;

class StepForwardButtonLabelTest extends TestCase
{
    public function testGetLabelForNormalOrder()
    {
        $quote = $this->getQuote(42);
        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getQuote')->willReturn($quote);

        $stepForwardButtonLabel = new StepForwardButtonLabel($checkoutState);
        $this->assertSame('Pay and Place Order', $stepForwardButtonLabel->getLabel($this->getBlock()));
    }

    public function testGetLabelForFreeOrder()
    {
        $quote = $this->getQuote(0);
        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getQuote')->willReturn($quote);

        $stepForwardButtonLabel = new StepForwardButtonLabel($checkoutState);
        $this->assertSame('Place Order', $stepForwardButtonLabel->getLabel($this->getBlock()));
    }

    private function getBlock(): AbstractBlock
    {
        $block = $this
            ->getMockBuilder(AbstractBlock::class)
            ->disableOriginalConstructor()
            ->addMethods([
                'getButtonLabelWithoutPayment',
                'getButtonLabel',
            ])
            ->getMock();

        $block->method('getButtonLabelWithoutPayment')->willReturn('Place Order');
        $block->method('getButtonLabel')->willReturn('Pay and Place Order');
        return $block;
    }

    private function getQuote(float $grandTotal): Quote
    {
        $quote = $this
            ->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->addMethods(['getGrandTotal'])
            ->getMock();

        $quote->method('getGrandTotal')->willReturn($grandTotal);
        return $quote;
    }
}
