<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Firstname;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[AppArea('frontend')]
class FirstnameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki.checkout.shipping-step.shipping-address.firstname';

    use CartFixture;
    use ProductFixture;


    public function testFirstNameComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('firstname', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $firstnameComponent = $this->getObjectManager()->get(Firstname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $firstnameComponent);

        $this->assertMagewireComponentHasErrors($firstnameComponent);
        $this->assertMagewireComponentHasError($firstnameComponent, 'Value is required');
    }

    public function testFirstNameComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('firstname', 'John');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $firstnameComponent = $this->getObjectManager()->get(Firstname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $firstnameComponent);

        $this->assertMagewireComponentHasNoErrors($firstnameComponent);
    }
}
