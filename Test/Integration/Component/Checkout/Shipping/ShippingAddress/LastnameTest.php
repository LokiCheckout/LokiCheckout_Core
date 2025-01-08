<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\Lastname;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 */
#[AppArea('frontend')]
class LastnameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.lastname';

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['lastname' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Lastname::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $component->validate();
        $this->assertComponentHasErrors($component);
        $this->assertComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['lastname' => 'Doe']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Lastname::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $this->assertComponentHasNoErrors($component);
    }
}
