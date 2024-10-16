<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Cache;
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

    public function testMiddleNameDisabled()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('middlename', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $this->assertMagewireComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/middlename_show', 1)]
    public function testMiddleNameComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('middlename', '');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $middlenameComponent = $this->getObjectManager()->get(Middlename::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $middlenameComponent);

        $this->assertMagewireComponentHasErrors($middlenameComponent);
        $this->assertMagewireComponentHasError($middlenameComponent, 'Value is required');
    }

    #[Config('customer/address/middlename_show', 1)]
    #[Cache('layout', false)]
    public function testMiddleNameComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->setData('middlename', 'Doe');
        $cart->addProduct($this->getProduct(), 1);

        $this->dispatchToCheckout();
        $middlenameComponent = $this->getObjectManager()->get(Middlename::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $middlenameComponent);

        $this->assertMagewireComponentHasNoErrors($middlenameComponent);
    }
}
