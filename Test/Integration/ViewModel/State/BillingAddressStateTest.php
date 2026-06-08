<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel\State;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class BillingAddressStateTest extends LokiCheckoutTestCase
{
    private function getSubject(): BillingAddressState
    {
        return $this->getObjectManager()->get(BillingAddressState::class);
    }

    #[ConfigFixture('general/country/default', 'NL')]
    public function testGetAddressAppliesDefaultCountryAndFlags(): void
    {
        $this->getCart();
        $address = $this->getSubject()->getAddress();

        $this->assertSame('NL', $address->getCountryId());
        $this->assertTrue((bool)$address->getIsBilling());
        $this->assertFalse((bool)$address->getIsShipping());
    }

    public function testSetAndGetValue(): void
    {
        $this->getCart();
        $this->getSubject()->set('city', 'Utrecht');

        $this->assertSame('Utrecht', $this->getSubject()->get('city'));
    }

    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    public function testSetMirrorsToShippingWhenSame(): void
    {
        $cart = $this->getCart();
        $cart->getShippingAddress()->setSameAsBilling(true);

        $this->getSubject()->set('city', 'Eindhoven');

        $this->assertSame('Eindhoven', $cart->getShippingAddress()->getData('city'));
    }
}
