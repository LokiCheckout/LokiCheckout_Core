<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Middlename;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[AppArea('frontend')]
class MiddlenameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki.checkout.shipping-step.shipping-address.middlename';

    use CartFixture;
    use ProductFixture;

    public function testDisabled()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('middlename', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $this->assertMagewireComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/middlename_show', 1, 'store', 'default')]
    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('middlename', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $component = $this->getObjectManager()->get(Middlename::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    #[Config('customer/address/middlename_show', 1, 'store', 'default')]
    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('middlename', 'Doe');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $component = $this->getObjectManager()->get(Middlename::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }
}
