<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Prefix;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[AppArea('frontend')]
class PrefixTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-step.shipping-address.prefix';

    use CartFixture;
    use ProductFixture;

    public function testDisabled()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['prefix' => '']);
        $this->dispatchToCheckout($cart);

        $this->assertMagewireComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/prefix_show', 1, 'store', 'default')]
    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['prefix' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Prefix::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    #[Config('customer/address/prefix_show', 1, 'store', 'default')]
    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['prefix' => 'Mr']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Prefix::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    #[Config('customer/address/prefix_show', 1, 'store', 'default')]
    public function testComponentWithTooLargeValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['prefix' => 'sdfgpdfgjdfgjdfgldfgg']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Prefix::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
    }

    #[Config('customer/address/prefix_show', 1, 'store', 'default')]
    #[Config('customer/address/prefix_options', 'Mr,Mrs', 'store', 'default')]
    public function testComponentWithWrongValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['prefix' => 'Miss']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Prefix::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
        $this->assertMagewireComponentHasError($component, 'Invalid prefix');
    }
}
