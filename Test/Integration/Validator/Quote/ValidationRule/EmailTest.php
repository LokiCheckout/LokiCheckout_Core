<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Quote\ValidationRule;

use LokiCheckout\Core\Validator\Quote\ValidationRule\Email;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\QuoteFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class EmailTest extends TestCase
{
    private Email $subject;
    private QuoteFactory $quoteFactory;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(Email::class);
        $this->quoteFactory = ObjectManager::getInstance()->get(QuoteFactory::class);
    }

    public function testValidEmailHasNoErrors(): void
    {
        $quote = $this->quoteFactory->create();
        $quote->setCustomerEmail('valid@example.com');

        $results = $this->subject->validate($quote);
        $result = array_shift($results);

        $this->assertTrue($result->isValid());
        $this->assertSame([], $result->getErrors());
    }

    public function testInvalidEmailHasError(): void
    {
        $quote = $this->quoteFactory->create();
        $quote->setCustomerEmail('not-an-email');

        $results = $this->subject->validate($quote);
        $result = array_shift($results);

        $this->assertFalse($result->isValid());
        $this->assertNotEmpty($result->getErrors());
        $this->assertStringContainsString('Email address is invalid', (string)$result->getErrors()[0]);
    }

    public function testEmptyEmailHasError(): void
    {
        $quote = $this->quoteFactory->create();

        $results = $this->subject->validate($quote);
        $result = array_shift($results);

        $this->assertFalse($result->isValid());
    }
}
