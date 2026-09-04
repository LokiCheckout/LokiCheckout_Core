<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Other\Recaptcha;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class RecaptchaPageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.recaptcha.place-order';

    final public function testComponentNotOnPageWithoutConfiguration(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME, true);
    }

    #[ConfigFixture('recaptcha_frontend/type_for/place_order', 'recaptcha', 'store', 'default')]
    final public function testComponentNotOnPageWithoutKeys(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME, true);
    }

    #[ConfigFixture('recaptcha_frontend/type_for/place_order', 'recaptcha', 'store', 'default')]
    #[ConfigFixture('recaptcha_frontend/type_recaptcha/public_key', 'site-key', 'website', 'base')]
    #[ConfigFixture('recaptcha_frontend/type_recaptcha/private_key', 'secret-key', 'website', 'base')]
    final public function testComponentOnPage(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, false);
    }
}
