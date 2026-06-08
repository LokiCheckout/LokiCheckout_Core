<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Cart;

use LokiCheckout\Core\Util\Cart\ItemConvertor;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
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
    DataFixture(ProductFixture::class, ['sku' => 'item-convert-sku', 'price' => 25.00], 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 3]),
]
class ItemConvertorTest extends TestCase
{
    public function testToArray(): void
    {
        $storage = DataFixtureStorageManager::getStorage();
        $cartId = (int)$storage->get('cart')->getId();

        $cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        /** @var Quote $quote */
        $quote = $cartRepository->get($cartId);
        $items = $quote->getAllVisibleItems();
        $item = array_shift($items);

        $subject = ObjectManager::getInstance()->get(ItemConvertor::class);
        $result = $subject->toArray($item);

        $this->assertSame($item->getId(), $result['id']);
        $this->assertSame('item-convert-sku', $result['sku']);
        $this->assertSame($item->getName(), $result['name']);
        $this->assertSame(3.0, (float)$result['qty']);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('price_incl_tax', $result);
        $this->assertArrayHasKey('additional_data', $result);
    }
}
