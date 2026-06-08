<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use LokiCheckout\Core\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use LokiCheckout\Core\Plugin\FixFreeShippingSubtotalPlugin;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, ['price' => 50], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class FixFreeShippingSubtotalPluginTest extends LokiCheckoutTestCase
{
    private function getSubject(): FixFreeShippingSubtotalPlugin
    {
        return $this->getObjectManager()->get(FixFreeShippingSubtotalPlugin::class);
    }

    private function getRepository(): ShippingMethodsRepository
    {
        return $this->createMock(ShippingMethodsRepository::class);
    }

    private function freeShippingMethod(): ShippingMethodInterface
    {
        $method = $this->createMock(ShippingMethodInterface::class);
        $method->method('getMethodCode')->willReturn('freeshipping');
        return $method;
    }

    #[ConfigFixture('carriers/freeshipping/free_shipping_subtotal', 100)]
    public function testFreeShippingRemovedWhenSubtotalBelowThreshold(): void
    {
        $this->getCart();
        $methods = ['freeshipping_freeshipping' => $this->freeShippingMethod()];

        $result = $this->getSubject()->afterGetShippingMethods($this->getRepository(), $methods);

        $this->assertArrayNotHasKey('freeshipping_freeshipping', $result);
    }

    #[ConfigFixture('carriers/freeshipping/free_shipping_subtotal', 10)]
    public function testFreeShippingKeptWhenSubtotalAboveThreshold(): void
    {
        $this->getCart();
        $methods = ['freeshipping_freeshipping' => $this->freeShippingMethod()];

        $result = $this->getSubject()->afterGetShippingMethods($this->getRepository(), $methods);

        $this->assertArrayHasKey('freeshipping_freeshipping', $result);
    }
}
