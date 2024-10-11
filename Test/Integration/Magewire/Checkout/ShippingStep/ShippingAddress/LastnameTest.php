<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Lastname;
use Yireo\FriggaCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\FriggaCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\FriggaCheckout\Test\Integration\FriggaTestCase;

#[AppArea('frontend')]
class LastnameTest extends FriggaTestCase
{
    const BLOCK_NAME = 'frigga.checkout.shipping-step.shipping-address.lastname';

    use CartFixture;
    use ProductFixture;


    public function testLastNameComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('lastname', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $lastnameComponent = $this->getObjectManager()->get(Lastname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $lastnameComponent);

        $this->assertMagewireComponentHasErrors($lastnameComponent);
        $this->assertMagewireComponentHasError($lastnameComponent, 'Value is required');
    }

    public function testLastNameComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('lastname', 'Doe');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $lastnameComponent = $this->getObjectManager()->get(Lastname::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $lastnameComponent);

        $this->assertMagewireComponentHasNoErrors($lastnameComponent);
    }
}
