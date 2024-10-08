<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Test\Fixture\Category as CategoryFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Firstname;
use Yireo\FriggaCheckout\Test\Integration\FriggaTestCase;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Checkout\Test\Fixture\SetBillingAddress as SetBillingAddressFixture;
use Magento\Checkout\Test\Fixture\SetShippingAddress as SetShippingAddressFixture;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;

#[AppArea('frontend')]
class FirstnameTest extends FriggaTestCase
{
    const BLOCK_NAME = 'frigga.checkout.shipping-step.shipping-address.firstname';

    #[
        AppIsolation(true),
        DbIsolation(true),
        DataFixture(CategoryFixture::class, as: 'c1'),
        DataFixture(ProductFixture::class, [
            'sku' => 's1',
            'price' => 42,
            'category_ids' => ['$c1.id$'],
            'status' => Status::STATUS_ENABLED,
            'salable' => 1,
            'is_saleable' => 1,
            'stock_item' => ['manage_stock' => 1, 'is_in_stock' => 1, 'qty' => 42]
        ], 'product1'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(
            AddProductToCartFixture::class,
            ['cart_id' => '$cart.id$', 'product_id' => '$product1.id$', 'qty' => 1],
            'item1'
        )
    ]
    /**
     * DataFixture(SetBillingAddressFixture::class, ['cart_id' => '$cart.id$']),
     * DataFixture(SetShippingAddressFixture::class, ['cart_id' => '$cart.id$']),
     */
    public function testComponentIsPresentOnPage()
    {
        //$firstname = $this->getComponentByBlockName('firstname');
        //$firstname->updated('John', 'value');

        // @todo: Check if the cart is there
        //$cart = $this->_objectManager->get(CartInterface::class);

        $this->dispatchToCart();
        $body = $this->getResponse()->getBody();
        $this->assertMagewireBlockExistsInHtml(self::BLOCK_NAME, $body);
        $this->assertMagewireComponentResolves(self::BLOCK_NAME, Firstname::class, ['checkout_index_index']);

    }
}
