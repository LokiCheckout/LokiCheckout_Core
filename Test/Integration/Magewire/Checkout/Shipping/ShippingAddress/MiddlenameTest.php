<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\Middlename;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 */
#[AppArea('frontend')]
class MiddlenameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.middlename';

    public function testDisabled()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['middlename' => '']);
        $this->dispatchToCheckout($cart);

        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/middlename_show', 1, 'store', 'default')]
    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['middlename' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Middlename::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $component->validate();
        $this->assertComponentHasNoErrors($component);
    }

    #[Config('customer/address/middlename_show', 1, 'store', 'default')]
    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['middlename' => 'van']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Middlename::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $component->validate();
        $this->assertComponentHasNoErrors($component);
    }
}
