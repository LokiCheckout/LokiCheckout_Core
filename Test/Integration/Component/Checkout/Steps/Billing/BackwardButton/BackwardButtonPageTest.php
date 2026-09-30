<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Steps\Billing\BackwardButton;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Fixture\ShippingAddressFixture;

#[AppArea('frontend')]
final class BackwardButtonPageTest extends LokiCheckoutTestCase
{
    protected ?string $stepCode = 'billing';

    const BLOCK_NAME = 'loki-checkout.steps.billing.backward-button';

    // Fixtures are disabled while this test is skipped, because Magento applies them before the test body runs (which
    // is costly). Their imports are kept on purpose, so that re-enabling this test only requires uncommenting.
    // #[ConfigFixture('loki_checkout/general/theme', 'onepage', 'store', 'default')]
    // #[DataFixture(ProductFixture::class, as: 'product')]
    // #[DataFixture(GuestCartFixture::class, as: 'cart')]
    // #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    // #[DataFixture(ShippingAddressFixture::class, ['cart_id' => '$cart.id$'])]
    final public function testComponentOnPage(): void
    {
        $this->markTestSkipped();
        $this->dispatchToCheckout();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, false);
    }
}
