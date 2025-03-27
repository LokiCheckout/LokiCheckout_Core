<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Address\Select;

use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressSelect\AddressSelectRepository;
use Yireo\LokiCheckout\Test\Fixture\CustomerLoggedInFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutRepositoryTestCase;

#[
    DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer'),
    DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$']),
    AppArea('frontend'),
]
class SelectRepositoryTest extends LokiCheckoutRepositoryTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.select';

    public function testGetValue()
    {
        $component = $this->getComponent(self::BLOCK_NAME);

        /** @var AddressSelectRepository $repository */
        $repository = $component->getRepository();
        $value = $repository->getValue();
        $this->assertSame('', $value);
    }
}
