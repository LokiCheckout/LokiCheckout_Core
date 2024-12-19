<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\Firstname;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 * @magentoAppArea frontend
 */
class FirstnameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.firstname';

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['firstname' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Firstname::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $component->validate();
        $this->assertComponentHasErrors($component);
        $this->assertComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['firstname' => 'John']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Firstname::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $this->assertComponentHasNoErrors($component);
    }
}
