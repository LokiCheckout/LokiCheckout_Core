<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel\State;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[
    DataFixture(GuestCartFixture::class, as: 'cart'),
    AppArea('frontend'),
]
class ShippingAddressStateTest extends LokiCheckoutTestCase
{
    private function getSubject(): ShippingAddressState
    {
        return $this->getObjectManager()->get(ShippingAddressState::class);
    }

    #[ConfigFixture('general/country/default', 'NL')]
    public function testGetAddressAppliesDefaultCountryAndFlags(): void
    {
        $this->getCart();
        $address = $this->getSubject()->getAddress();

        $this->assertSame('NL', $address->getCountryId());
        $this->assertTrue((bool)$address->getIsShipping());
        $this->assertFalse((bool)$address->getIsBilling());
    }

    public function testSetAndGetValue(): void
    {
        $this->getCart();
        $this->getSubject()->set('city', 'Groningen');

        $this->assertSame('Groningen', $this->getSubject()->get('city'));
    }

    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    public function testSetMirrorsToBillingWhenSame(): void
    {
        $cart = $this->getCart();
        $cart->getShippingAddress()->setSameAsBilling(true);

        $this->getSubject()->set('city', 'Maastricht');

        $this->assertSame('Maastricht', $cart->getBillingAddress()->getData('city'));
    }
}
