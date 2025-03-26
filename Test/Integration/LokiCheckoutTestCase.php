<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Response\Http;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiCheckoutAccountType\Test\Integration\Component\Checkout\Address\AccountType\AccountTypePageTest;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ComponentRepositoryInterface;
use Yireo\LokiComponents\Test\Integration\LokiComponentsTestCase;

class LokiCheckoutTestCase extends LokiComponentsTestCase
{
    use CartFixture;

    protected $themeName = 'loki_checkout_theme_onestep';

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertModuleIsEnabled('Yireo_LokiCheckout');
        $this->assertModuleIsEnabled('Yireo_LokiFieldComponents');
        $this->assertStoreConfigValueEquals(1, 'yireo_loki_checkout/general/enable');
    }

    public function getCheckoutComponent(string $blockName): ComponentInterface
    {
        return $this->getComponentWithBlock($blockName, ['default', 'loki_components', 'loki_field_components', 'loki_checkout', $this->themeName]);
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
        /** @var CartInterface $cart */
        $cart = $this->fixtures->get('cart');
        $this->assertInstanceOf(CartInterface::class, $cart);

        $cartId = $cart->getId();
        $cart = $this->getObjectManager()->get(CartRepositoryInterface::class)->get($cartId);

        $this->assertNotEmpty($cart->getId());

        $checkoutState = $this->getObjectManager()->get(CheckoutState::class);
        $checkoutState->setQuoteId((int)$cart->getId());

        $checkoutSession = $this->getObjectManager()->get(CheckoutSession::class);
        $checkoutSession->replaceQuote($cart);

        return $cart;
    }

    protected function assertRepositorySave(ComponentInterface $component, mixed $value)
    {
        $repository = $component->getRepository();
        $this->assertInstanceOf(ComponentRepositoryInterface::class, $repository);

        $repository->saveValue($value);
        $this->assertSame($value, $repository->getValue());
    }
}
