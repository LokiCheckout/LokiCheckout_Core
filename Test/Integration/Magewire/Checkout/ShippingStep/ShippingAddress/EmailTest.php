<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Email;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[AppArea('frontend')]
class EmailTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-step.email';

    use CartFixture;
    use ProductFixture;

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['email' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Email::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
        $this->assertMagewireComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->setData('email', 'john@example.com');
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Email::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $this->assertMagewireComponentHasNoErrors($component);
    }
}
