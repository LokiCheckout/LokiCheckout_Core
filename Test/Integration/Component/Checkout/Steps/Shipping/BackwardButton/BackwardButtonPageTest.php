<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Steps\Shipping\BackwardButton;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

#[AppArea('frontend')]
final class BackwardButtonPageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.steps.shipping.backward-button';

    #[DataFixture(ProductFixture::class, as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    final public function testComponentNotOnPage(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME, true);
    }
}
