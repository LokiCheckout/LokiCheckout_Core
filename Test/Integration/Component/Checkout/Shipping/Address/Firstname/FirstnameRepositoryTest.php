<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\Firstname;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    AppArea('frontend'),
]
class FirstnameRepositoryTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.firstname';

    public function testSaveToQuote(): void
    {
        $this->getCart();

        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertRepositorySave($component, 'John');

        $this->assertSame('John', $this->getQuote()->getShippingAddress()->getFirstname());
    }

    public static function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['John', true, []],
        ];
    }
}
