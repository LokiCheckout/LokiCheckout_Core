<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\Shipping\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\Company;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[AppArea('frontend')]
class CompanyTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.company';

    use CartFixture;
    use ProductFixture;

    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['company' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Company::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['company' => 'Yireo']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Company::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }
}
