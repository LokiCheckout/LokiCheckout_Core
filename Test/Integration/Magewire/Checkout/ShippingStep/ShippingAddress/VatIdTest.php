<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\Shipping\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\VatId;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;
use Magento\TestFramework\Fixture\Config;

#[AppArea('frontend')]
class VatIdTest extends LokiTestCase
{

    const BLOCK_NAME = 'loki-checkout.shipping-address.vat_id';

    use CartFixture;
    use ProductFixture;

    public function testDisabled()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['vat_id' => '']);
        $this->dispatchToCheckout($cart);

        $this->assertMagewireComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/taxvat_show', 1, 'store', 'default')]
    public function testComponentWithUsVatId()
    {
        $cart = $this->getGuestCart($this->getProduct());
        $cart->getShippingAddress()->addData(['country_id' => 'US', 'vat_id' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(VatId::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME, $component);

        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }
}
