<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Controller\Index;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * Only the test that renders the checkout requires a cart with products, so fixtures are added at method level
 */
#[AppArea('frontend')]
class IndexTest extends LokiCheckoutTestCase
{
    #[DataFixture(ProductFixture::class, as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testCheckoutPageRendersWithQuote(): void
    {
        $this->dispatchToCheckout();

        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $this->assertNotEmpty($this->getResponse()->getBody());
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testRedirectsToCartWhenNoQuote(): void
    {
        $this->dispatch('checkout/index/index');

        $this->assertSame(302, $this->getResponse()->getHttpResponseCode());
        $location = $this->getResponse()->getHeader('Location');
        $this->assertNotFalse($location);
        $this->assertStringContainsString('checkout/cart', $location->getFieldValue());
    }
}
