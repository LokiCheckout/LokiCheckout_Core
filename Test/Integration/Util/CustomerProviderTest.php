<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Test\Fixture\CustomerAddressFixture;
use LokiCheckout\Core\Test\Fixture\CustomerFixture;
use LokiCheckout\Core\Test\Fixture\CustomerLoggedInFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\CustomerProvider;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[
    DbIsolation(true),
    AppArea('frontend'),
]
class CustomerProviderTest extends LokiCheckoutTestCase
{
    public function testGuestIsNotLoggedIn(): void
    {
        $subject = $this->getObjectManager()->get(CustomerProvider::class);

        $this->assertFalse($subject->isLoggedIn());
        $this->assertSame([], $subject->getAddresses());
    }

    #[
        DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer'),
        DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$']),
        DataFixture(CustomerAddressFixture::class, ['customer_id' => '$customer.id$']),
    ]
    public function testLoggedInCustomerAddresses(): void
    {
        $subject = $this->getObjectManager()->get(CustomerProvider::class);

        $this->assertTrue($subject->isLoggedIn());
        $this->assertNotEmpty($subject->getAddresses());
    }
}
