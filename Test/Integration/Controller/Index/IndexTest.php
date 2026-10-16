<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Controller\Index;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Test\Integration\Stub\AppViewDependencyStub;
use LokiCheckout\Core\Test\Integration\Stub\LayoutGenerateBlocksCounterStub;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\View as AppView;
use Magento\Framework\Event\Config\Data as EventConfigData;
use Magento\TestFramework\ObjectManager as TestObjectManager;
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

    /**
     * Building the layout twice (for instance, when a second page result replaces the builder of the shared page
     * config) re-reads all layout XML on top of the existing structure, which removes child blocks of redeclared
     * elements (like the Hyvä scripts in "head.additional")
     */
    #[DataFixture(ProductFixture::class, as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testCheckoutLayoutIsGeneratedOnlyOnce(): void
    {
        $eventConfig = $this->getObjectManager()->get(EventConfigData::class);
        $this->assertInstanceOf(EventConfigData::class, $eventConfig);
        $eventConfig->merge([
            'layout_generate_blocks_before' => [
                'loki_checkout_test_app_view' => [
                    'instance' => AppViewDependencyStub::class,
                    'name' => 'loki_checkout_test_app_view',
                ],
            ],
            'layout_generate_blocks_after' => [
                'loki_checkout_test_counter' => [
                    'instance' => LayoutGenerateBlocksCounterStub::class,
                    'name' => 'loki_checkout_test_counter',
                ],
            ],
        ]);

        // In a real request, App\View is not instantiated before the controller is executed
        $objectManager = $this->getObjectManager();
        $this->assertInstanceOf(TestObjectManager::class, $objectManager);
        $objectManager->removeSharedInstance(AppView::class);

        $this->dispatchToCheckout();
        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());

        $counter = $this->getObjectManager()->get(LayoutGenerateBlocksCounterStub::class);
        $this->assertInstanceOf(LayoutGenerateBlocksCounterStub::class, $counter);
        $this->assertSame(1, $counter->getCount(), 'Checkout layout was generated more than once');
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

    #[DataFixture(ProductFixture::class, as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    #[ConfigFixture('checkout/options/onepage_checkout_enabled', 0, 'store', 'default')]
    public function testRedirectsToCartWhenOnepageCheckoutIsDisabled(): void
    {
        $this->assertLokiCheckoutIsEnabled();
        $this->getCart();
        $this->dispatch('checkout/index/index');

        $this->assertSame(302, $this->getResponse()->getHttpResponseCode());
        $location = $this->getResponse()->getHeader('Location');
        $this->assertNotFalse($location);
        $this->assertStringContainsString('checkout/cart', $location->getFieldValue());
    }
}
