<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\Firstname;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 * @magentoAppArea frontend
 */
class FirstnameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.firstname';

    use CartFixture;

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['firstname' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Firstname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
        $this->assertMagewireComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['firstname' => 'John']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Firstname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $this->assertMagewireComponentHasNoErrors($component);
    }
}
