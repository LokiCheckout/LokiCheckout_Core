<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\VatId;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;
use Magento\TestFramework\Fixture\Config;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 */
#[AppArea('frontend')]
class VatIdTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.vat_id';

    public function testDisabled()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['vat_id' => '']);
        $this->dispatchToCheckout($cart);

        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/taxvat_show', 1, 'store', 'default')]
    public function testComponentWithUsVatId()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['country_id' => 'US', 'vat_id' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(VatId::class);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);

        $component->validate();
        $this->assertComponentHasNoErrors($component);
    }
}
