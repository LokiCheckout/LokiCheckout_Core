<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Component;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Validator\Component\OptionsValidator;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * The validator only requires the component, not a rendered checkout page nor a cart with products. All values are
 * validated within a single test (reporting all mismatches at once), because every test carries significant overhead.
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    AppArea('frontend'),
]
class OptionsValidatorTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_gender';

    #[
        ConfigFixture('customer/address/gender_show', 'req', 'store', 'default'),
        ConfigFixture('customer/address/gender_show', 'req'),
    ]
    public function testValidate(): void
    {
        $this->getCart();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $subject = $this->getObjectManager()->get(OptionsValidator::class);

        $expectedResults = [
            '' => true,
            'male' => true,
            'not-a-valid-option' => ['Not a valid option'],
        ];

        $actualResults = [];
        foreach (array_keys($expectedResults) as $value) {
            $actualResults[$value] = $subject->validate((string)$value, $component);
        }

        $this->assertSame($expectedResults, $actualResults);
    }
}
