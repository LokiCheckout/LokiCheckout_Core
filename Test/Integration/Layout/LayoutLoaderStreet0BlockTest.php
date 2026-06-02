<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Util\Controller\LayoutLoader;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
class LayoutLoaderStreet0BlockTest extends AbstractLayoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout.shipping.address.street0';

    public function testStreet0BlockExistsAfterLoadingLayoutViaLayoutLoader(): void
    {
        $layout = $this->getLayout();
        $block = $layout->getBlock(self::BLOCK_NAME);
        $this->assertInstanceOf(
            AbstractBlock::class,
            $block,
            'Block "' . self::BLOCK_NAME . '" was not found'
        );
    }

    public function testNonExistentBlockIsNotFoundAfterLoadingLayout(): void
    {
        $layout = $this->getLayout();
        $this->assertFalse(
            $layout->getBlock('loki-checkout.shipping.address.does-not-exist'),
            'Unexpectedly found a non-existent block in the loaded layout'
        );
    }
}
