<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Steps\One;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

// Fixtures are disabled while this test is skipped, because Magento applies them before the test body runs (which
// is costly). Their imports are kept on purpose, so that re-enabling this test only requires uncommenting.
// #[DataFixture(ProductFixture::class, as: 'product')]
// #[DataFixture(GuestCartFixture::class, as: 'cart')]
// #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class OnePageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.steps.one';

    // #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    final public function testComponentOnPage(): void
    {
        $this->markTestIncomplete('WIP');
        $this->dispatchToCheckout();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, false);
    }
}
