<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Component;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Validator\Component\GenderValidator;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class GenderValidatorTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_gender';

    #[
        ConfigFixture('customer/address/gender_show', 'req', 'store', 'default'),
        ConfigFixture('customer/address/gender_show', 'req'),
    ]
    public function testEmptyValueIsValid(): void
    {
        $this->dispatchToCheckout();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $subject = $this->getObjectManager()->get(GenderValidator::class);

        $this->assertTrue($subject->validate('', $component));
    }

    #[
        ConfigFixture('customer/address/gender_show', 'req', 'store', 'default'),
        ConfigFixture('customer/address/gender_show', 'req'),
    ]
    public function testKnownGenderIsValid(): void
    {
        $this->dispatchToCheckout();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $subject = $this->getObjectManager()->get(GenderValidator::class);

        $this->assertTrue($subject->validate('male', $component));
    }

    #[
        ConfigFixture('customer/address/gender_show', 'req', 'store', 'default'),
        ConfigFixture('customer/address/gender_show', 'req'),
    ]
    public function testUnknownGenderIsInvalid(): void
    {
        $this->dispatchToCheckout();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $subject = $this->getObjectManager()->get(GenderValidator::class);

        $result = $subject->validate('not-a-gender', $component);
        $this->assertSame(['Invalid gender'], $result);
    }
}
