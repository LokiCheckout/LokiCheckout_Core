<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\CustomerData\Checkout;

use LokiCheckout\Core\CustomerData\Checkout\QuoteDataCollector;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class QuoteDataCollectorTest extends TestCase
{
    private QuoteDataCollector $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(QuoteDataCollector::class);
    }

    public function testGetDataMapsQuoteFields(): void
    {
        $quote = $this->createQuote();
        $quote->setCustomerFirstname('Jane');
        $quote->setCustomerLastname('Doe');
        $quote->setCustomerEmail('jane@example.com');

        $data = $this->subject->getData($quote);

        $this->assertSame('Jane', $data['customer_firstname']);
        $this->assertSame('Doe', $data['customer_lastname']);
        $this->assertSame('jane@example.com', $data['customer_email']);
        $this->assertSame('Jane Doe', $data['customer_fullname']);
        $this->assertArrayHasKey('grand_total', $data);
        $this->assertArrayHasKey('currency_code', $data);
    }

    public function testFullnameIncludesMiddlename(): void
    {
        $quote = $this->createQuote();
        $quote->setCustomerFirstname('Jane');
        $quote->setCustomerMiddlename('Q');
        $quote->setCustomerLastname('Doe');

        $data = $this->subject->getData($quote);
        $this->assertSame('Jane Q Doe', $data['customer_fullname']);
    }

    private function createQuote(): Quote
    {
        return ObjectManager::getInstance()->get(QuoteFactory::class)->create();
    }
}
