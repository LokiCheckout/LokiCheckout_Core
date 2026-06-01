<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use Loki\Components\Layout\LayoutHandlerComposite;
use Loki\Components\Util\Controller\LayoutLoader;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Test\Integration\LokiComponentsTestCase;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;

abstract class AbstractLayoutTestCase extends LokiComponentsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $om = Bootstrap::getObjectManager();
        $fixtures = $om->get(DataFixtureStorageManager::class)->getStorage();
        $cart = $fixtures->get('cart');

        $cartId = (int)$cart->getId();
        $cart = $om->get(CartRepositoryInterface::class)->get($cartId);

        $checkoutState = $om->get(CheckoutState::class);
        $checkoutState->setQuote($cart);
        $checkoutState->setQuoteId($cartId);

        $om->get(CheckoutSession::class)->replaceQuote($cart);
    }

    public function getLayout(): LayoutInterface
    {
        $handles = ['checkout_index_index'];
        $layoutHandlerComposite = ObjectManager::getInstance()->get(LayoutHandlerComposite::class);
        $handles = $layoutHandlerComposite->getHandles($handles);
        $layoutLoader = ObjectManager::getInstance()->get(LayoutLoader::class);
        return $layoutLoader->load($handles);
    }
}
