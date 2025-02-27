<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Response\Http;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiComponents\Test\Integration\LokiComponentsTestCase;

class LokiCheckoutTestCase extends LokiComponentsTestCase
{
    use CartFixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertModuleIsEnabled('Yireo_LokiCheckout');
        $this->assertModuleIsEnabled('Yireo_LokiFieldComponents');
        $this->assertStoreConfigValueEquals(1, 'yireo_loki_checkout/general/enable');
    }

    public function dispatchToCheckout(?CartInterface $cart = null)
    {
        if ($cart === null) {
            $cart = $this->getCart();
        }

        $this->assertTrue($cart->getItemsCount() > 0);
        $this->getObjectManager()->get(LayoutInterface::class)->getUpdate()->addHandle([
            'checkout_index_index',
            'loki_checkout',
            'loki_checkout_theme_onestep',
        ]);

        $this->dispatch('checkout/index/index');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertNotEmpty($response->getBody(), 'No body');
    }

    protected function getCart(): CartInterface
    {
        $cart = $this->fixtures->get('cart');
        $this->assertInstanceOf(CartInterface::class, $cart);
        $this->assertNotEmpty($cart->getId());

        $checkoutState = $this->getObjectManager()->get(CheckoutState::class);
        $checkoutState->setQuoteId((int)$cart->getId());

        $checkoutSession = $this->getObjectManager()->get(CheckoutSession::class);
        $checkoutSession->replaceQuote($cart);

        return $cart;
    }
}
