<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Lastname;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[AppArea('frontend')]
class LastnameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki.checkout.shipping-step.shipping-address.lastname';

    use CartFixture;
    use ProductFixture;


    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('lastname', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $component = $this->getObjectManager()->get(Lastname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
        $this->assertMagewireComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('lastname', 'Doe');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $component = $this->getObjectManager()->get(Lastname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $this->assertMagewireComponentHasNoErrors($component);
    }
}
