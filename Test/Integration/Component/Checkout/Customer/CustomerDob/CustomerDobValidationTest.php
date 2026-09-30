<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Customer\CustomerDob;

use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;

/**
 * Validation only requires the component, not a rendered checkout page nor a cart with products. All values are
 * validated within a single test, because every single test (with its fixtures) carries significant overhead.
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    AppArea('frontend'),
]
class CustomerDobValidationTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_dob';

    #[ConfigFixture('customer/address/dob_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'opt')]
    public function testComponentNoValidators(): void
    {
        $this->getCart();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['date', 'past_date'], $component);
        $this->assertComponentNoValidators(['required'], $component);
    }

    #[ConfigFixture('customer/address/dob_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'req')]
    public function testComponentValidation(): void
    {
        $this->getCart();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required', 'date', 'past_date'], $component);
        $this->assertComponentValidationCases($component, self::getTestData());
    }

    public static function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['foobar', false, ['Invalid date format']],
            ['1978-01-02', true, []],
            [((int)date('Y') - 1).'-01-02', false, ['You need to be at least 10 years old']],
            [((int)date('Y') + 12).'-01-02', false, ['Date needs to be in the past']],
        ];
    }
}
