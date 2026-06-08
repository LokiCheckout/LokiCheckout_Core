<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\CustomerData\Checkout;

use LokiCheckout\Core\CustomerData\Checkout\CartItemDataCollector;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

#[
    DbIsolation(true),
    AppArea('frontend'),
    DataFixture(ProductFixture::class, ['sku' => 'cart-item-data-sku', 'price' => 15], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 2]),
]
class CartItemDataCollectorTest extends TestCase
{
    public function testGetDataMapsCartItem(): void
    {
        $cartId = (int)DataFixtureStorageManager::getStorage()->get('cart')->getId();
        $quote = ObjectManager::getInstance()->get(CartRepositoryInterface::class)->get($cartId);
        $items = $quote->getAllVisibleItems();
        $item = array_shift($items);

        $subject = ObjectManager::getInstance()->get(CartItemDataCollector::class);
        $data = $subject->getData($item);

        $this->assertSame('cart-item-data-sku', $data['sku']);
        $this->assertSame(2.0, (float)$data['qty']);
        $this->assertArrayHasKey('item_id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('price', $data);
        $this->assertSame('simple', $data['product_type']);
    }
}
