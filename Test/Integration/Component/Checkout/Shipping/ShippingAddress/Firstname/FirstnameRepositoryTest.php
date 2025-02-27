<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress\Firstname;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutTestCase;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Component\ComponentRepositoryInterface;

#[
    AppIsolation(true),
    DbIsolation(true),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1]),
    AppArea('frontend'),
]
class FirstnameRepositoryTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.firstname';

    public function testSaveToQuote(): void
    {
        $this->getCart();
        $componentRegistry = ObjectManager::getInstance()->get(ComponentRegistry::class);
        $component = $componentRegistry->getComponentByName(self::BLOCK_NAME);
        $this->assertRepositorySave($component, 'John');
    }

    public function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['John', true, []],
        ];
    }
}
