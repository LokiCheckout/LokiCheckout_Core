<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\Shipping\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config;
use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\Prefix;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;
use Yireo\LokiCheckout\ViewModel\OptionModel\PrefixOptionModel;

/**
 * @magentoDataFixture Magento/Checkout/_files/quote_with_simple_products.php
 */
#[AppArea('frontend')]
class PrefixTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping-address.prefix';

    #[Config('customer/address/prefix_show', 0, 'store', 'default')]
    public function testDisabled()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['prefix' => '']);
        $this->dispatchToCheckout($cart);

        $this->assertMagewireComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/prefix_show', 1, 'store', 'default')]
    public function testComponentWithoutValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['prefix' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Prefix::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME);

        $component->clearErrors();
        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    #[Config('customer/address/prefix_show', 1, 'store', 'default')]
    public function testComponentWithValue()
    {
        $cart = $this->getGuestCart();
        $cart->getShippingAddress()->addData(['prefix' => 'Mr']);
        $this->dispatchToCheckout($cart);

        $component = $this->getObjectManager()->get(Prefix::class);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME);

        $component->clearErrors();
        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    #[Config('customer/address/prefix_options', 'Mr,Mrs', 'store', 'default')]
    public function testComponentWithWrongOptionValue()
    {
        $component = $this->getDummyComponentInstance(Prefix::class);
        $this->assertContains('prefix', $component->getValidators());

        $prefixOptionModel = $this->getObjectManager()->get(PrefixOptionModel::class);
        $component->getParent()->setData('option_model', $prefixOptionModel);

        $component->value = 'Miss';
        $component->clearErrors();
        $component->validate();
        $this->assertMagewireComponentHasErrors($component);
        $this->assertMagewireComponentHasError($component, 'Invalid prefix');
    }
}
