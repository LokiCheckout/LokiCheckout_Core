<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Quote;

use Yireo\LokiCheckout\Component\Checkout\Quote\CustomerEmail;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 * @magentoAppArea frontend
 */
class CustomerEmailTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.email';

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['email' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(CustomerEmail::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $component->validate();
        $this->assertComponentHasErrors($component);
        $this->assertComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('email', 'john@example.com');
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(CustomerEmail::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $this->assertComponentHasNoErrors($component);
    }
}
