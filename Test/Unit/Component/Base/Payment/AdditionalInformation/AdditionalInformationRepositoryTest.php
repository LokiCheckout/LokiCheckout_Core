<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Component\Base\Payment\AdditionalInformation;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Payment\AdditionalInformation\AdditionalInformationRepository;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Payment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AdditionalInformationRepositoryTest extends TestCase
{
    private array $additionalInformation = [];

    public function testExistingEqualValueIsNotSaved(): void
    {
        $this->additionalInformation = ['foo' => 'bar'];
        $repository = $this->getRepository($this->getCheckoutState($this->never()));

        $this->assertSame('bar', $repository->getValue());
    }

    public function testDifferentValueIsSaved(): void
    {
        $this->additionalInformation = ['foo' => 'bar'];
        $repository = $this->getRepository($this->getCheckoutState($this->once()));

        $repository->saveValue('baz');
        $this->assertSame('baz', $this->additionalInformation['foo']);
    }

    public function testMissingKeyWithEmptyDefaultIsSaved(): void
    {
        $this->additionalInformation = [];
        $repository = $this->getRepository($this->getCheckoutState($this->once()));

        $this->assertSame('', $repository->getValue());
        $this->assertArrayHasKey('foo', $this->additionalInformation);
        $this->assertSame('', $this->additionalInformation['foo']);
    }

    public function testIntegerValueEqualToStoredStringIsNotSaved(): void
    {
        $this->additionalInformation = ['foo' => '1'];
        $repository = $this->getRepository($this->getCheckoutState($this->never()));

        $repository->saveValue(1);
        $this->assertSame('1', $this->additionalInformation['foo']);
    }

    private function getCheckoutState(object $saveQuoteExpectation): CheckoutState&MockObject
    {
        $payment = $this->createMock(Payment::class);
        $payment->method('getAdditionalInformation')
            ->willReturnCallback(fn () => $this->additionalInformation);
        $payment->method('setAdditionalInformation')
            ->willReturnCallback(function ($key, $value) use ($payment) {
                $this->additionalInformation[$key] = $value;
                return $payment;
            });

        $quote = $this->createMock(Quote::class);
        $quote->method('getPayment')->willReturn($payment);

        $checkoutState = $this->createMock(CheckoutState::class);
        $checkoutState->method('getQuote')->willReturn($quote);
        $checkoutState->expects($saveQuoteExpectation)->method('saveQuote');

        return $checkoutState;
    }

    private function getRepository(CheckoutState $checkoutState): AdditionalInformationRepository
    {
        $context = $this->getMockBuilder(CheckoutContext::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__call'])
            ->getMock();
        $context->method('__call')->with('getCheckoutState')->willReturn($checkoutState);

        return new class($context) extends AdditionalInformationRepository {
            public function __construct(
                private readonly CheckoutContext $checkoutContext
            ) {
            }

            protected function getContext(): CheckoutContext
            {
                return $this->checkoutContext;
            }

            public function getPropertyName(): string
            {
                return 'foo';
            }

            public function getDefaultValue(): mixed
            {
                return '';
            }
        };
    }
}
