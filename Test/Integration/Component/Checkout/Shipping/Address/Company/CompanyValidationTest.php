<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\Company;

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
class CompanyValidationTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.company';

    #[ConfigFixture('customer/address/company_show', 'opt')]
    public function testComponentNoValidators(): void
    {
        $this->getCart();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentNoValidators(['required'], $component);
    }

    #[ConfigFixture('customer/address/company_show', 'req')]
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
            ['John', true, []],
        ];
    }
}
