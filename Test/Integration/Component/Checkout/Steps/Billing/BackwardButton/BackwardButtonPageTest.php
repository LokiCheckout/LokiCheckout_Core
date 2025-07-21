<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Steps\Billing\BackwardButton;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use LokiCheckout\Core\Test\Fixture\ShippingAddressFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutPageTestCase;

#[AppArea('frontend')]
final class BackwardButtonPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    protected ?string $stepCode = 'billing';

    const BLOCK_NAME = 'loki-checkout.steps.billing.backward-button';

    #[ConfigFixture('loki_checkout/general/theme', 'onepage')]
    #[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[DataFixture(ShippingAddressFixture::class, ['cart_id' => '$cart.id$'])]
    final public function testComponentOnPage(): void
    {
        $this->markTestSkipped();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }
}
