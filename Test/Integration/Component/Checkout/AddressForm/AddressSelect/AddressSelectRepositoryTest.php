<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\AddressForm\AddressSelect;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Customer\Model\Session;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutTestCase;

#[
    AppIsolation(true),
    DbIsolation(true),
    DataFixture(CustomerFixture::class, ['id' => 1]),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1]),
    AppArea('frontend'),
]
class AddressSelectRepositoryTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.select';

    public function testGetValue()
    {
        /** @var Session $customerSession */
        $customerSession = ObjectManager::getInstance()->get(Session::class);
        $customerSession->isLoggedIn();
        $customerSession->setCustomerId(1);

        $component = $this->getComponent(self::BLOCK_NAME);
        $value = $component->getRepository()->getValue();
        $this->assertSame(0, $value);

        // @todo: Extend upon this test
    }
}
