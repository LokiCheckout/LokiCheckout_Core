<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\Quote;

use Yireo\LokiCheckout\Magewire\Checkout\Quote\CustomerEmail;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 * @magentoAppArea frontend
 */
class CustomerEmailTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.email';

    use CartFixture;

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['email' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(CustomerEmail::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
        $this->assertMagewireComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('email', 'john@example.com');
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(CustomerEmail::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $this->assertMagewireComponentHasNoErrors($component);
    }
}
