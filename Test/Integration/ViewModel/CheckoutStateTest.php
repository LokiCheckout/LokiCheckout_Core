<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CheckoutStateTest extends TestCase
{
    public function testCalculateTotalsIsNotSharedBetweenInstances(): void
    {
        $totals1 = $this->createMock(TotalsInterface::class);
        $totals2 = $this->createMock(TotalsInterface::class);

        $checkoutState1 = $this->createCheckoutState($this->createQuote(1001), [$totals1]);
        $checkoutState2 = $this->createCheckoutState($this->createQuote(1002), [$totals2]);

        $this->assertSame($totals1, $checkoutState1->calculateTotals());
        $this->assertSame($totals2, $checkoutState2->calculateTotals());
    }

    public function testCalculateTotalsIsRecalculatedAfterReset(): void
    {
        $totals1 = $this->createMock(TotalsInterface::class);
        $totals2 = $this->createMock(TotalsInterface::class);

        $quote = $this->createQuote(1003);
        $checkoutState = $this->createCheckoutState($quote, [$totals1, $totals2]);

        $this->assertSame($totals1, $checkoutState->calculateTotals());
        $this->assertSame($totals1, $checkoutState->calculateTotals());

        $checkoutState->reset();
        $checkoutState->setQuote($quote);
        $this->assertSame($totals2, $checkoutState->calculateTotals());
    }

    /**
     * @param TotalsInterface[] $totals
     */
    private function createCheckoutState(Quote $quote, array $totals): CheckoutState
    {
        $totalsInformationManagement = $this->createMock(TotalsInformationManagementInterface::class);
        $totalsInformationManagement->expects($this->exactly(count($totals)))
            ->method('calculate')
            ->willReturnOnConsecutiveCalls(...$totals);

        /** @var CheckoutState $checkoutState */
        $checkoutState = ObjectManager::getInstance()->create(CheckoutState::class, [
            'totalsInformationManagement' => $totalsInformationManagement,
        ]);

        $checkoutState->setQuoteId((int)$quote->getId());
        $checkoutState->setQuote($quote);

        return $checkoutState;
    }

    private function createQuote(int $quoteId): Quote
    {
        $quote = ObjectManager::getInstance()->get(QuoteFactory::class)->create();
        $quote->setId($quoteId);
        $quote->getShippingAddress()->setCountryId('NL');
        $quote->getBillingAddress()->setCountryId('NL');

        return $quote;
    }
}
