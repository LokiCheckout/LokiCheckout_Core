<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\Postcode;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Fixture\ShippingAddressFixture;

/**
 * Validation only requires the component, not a rendered checkout page nor a cart with products. All values are
 * validated within a single test, because every single test (with its fixtures) carries significant overhead.
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(ShippingAddressFixture::class, ['cart_id' => '$cart.id$', 'country_id' => 'NL']),
    AppArea('frontend'),
]
class PostcodeValidationTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.postcode';

    public function testComponentValidation(): void
    {
        $this->getCart();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required'], $component);
        $this->assertComponentValidationCases($component, self::getTestData());
    }

    public static function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['3743KJ', true, []],
            ['3743', false, ['needs to be in one of the following formats']],
        ];
    }
}
