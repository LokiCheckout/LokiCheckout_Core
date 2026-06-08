<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\CustomerData\Checkout;

use LokiCheckout\Core\CustomerData\Checkout\CustomerDataCollector;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CustomerDataCollectorTest extends TestCase
{
    private CustomerDataCollector $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(CustomerDataCollector::class);
    }

    public function testGuestData(): void
    {
        $data = $this->subject->getData(0);

        $this->assertSame(0, $data['id']);
        $this->assertSame(0, $data['group_id']);
        $this->assertArrayNotHasKey('customer', $data);
    }

    #[
        DbIsolation(true),
        DataFixture(CustomerFixture::class, as: 'customer'),
    ]
    public function testLoggedInCustomerData(): void
    {
        $customer = DataFixtureStorageManager::getStorage()->get('customer');
        $customerId = (int)$customer->getId();

        $data = $this->subject->getData($customerId);

        $this->assertSame($customerId, $data['id']);
        $this->assertArrayHasKey('customer', $data);
        $this->assertArrayHasKey('group_id', $data['customer']);
    }
}
