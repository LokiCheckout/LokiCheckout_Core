<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
class PreventDuplicateDomIdTest extends LokiCheckoutPageTestCase
{
    const TEST_DOM_ID = 'loki-checkout-steps-shipping';

    public function testForDuplicateDomIds()
    {
        $body = $this->getResponse()->getBody();
        $this->assertStringContainsString(self::TEST_DOM_ID, $body);

        $idAttribute = 'id="'.self::TEST_DOM_ID.'"';
        $this->assertStringContainsString($idAttribute, $body);
        $this->assertEquals(1, substr_count($body, $idAttribute));
    }
}
