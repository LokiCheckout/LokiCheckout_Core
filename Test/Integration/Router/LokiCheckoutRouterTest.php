<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Router;

use LokiCheckout\Core\Controller\Index\Index;
use LokiCheckout\Core\Router\LokiCheckoutRouter;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class LokiCheckoutRouterTest extends TestCase
{
    private function getRouter(): LokiCheckoutRouter
    {
        return ObjectManager::getInstance()->get(LokiCheckoutRouter::class);
    }

    private function createRequest(string $pathInfo): HttpRequest
    {
        $request = ObjectManager::getInstance()->create(HttpRequest::class);
        $request->setPathInfo($pathInfo);
        return $request;
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testMatchesCheckoutRoot(): void
    {
        $request = $this->createRequest('/checkout');
        $action = $this->getRouter()->match($request);

        $this->assertInstanceOf(ActionInterface::class, $action);
        $this->assertInstanceOf(Index::class, $action);
        $this->assertSame('checkout', $request->getRouteName());
        $this->assertSame('index', $request->getControllerName());
        $this->assertSame('index', $request->getActionName());
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testMatchesCheckoutIndex(): void
    {
        $request = $this->createRequest('/checkout/index');
        $this->assertInstanceOf(Index::class, $this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testMatchesCheckoutIndexIndex(): void
    {
        $request = $this->createRequest('/checkout/index/index');
        $this->assertInstanceOf(Index::class, $this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testDoesNotMatchCheckoutCart(): void
    {
        $request = $this->createRequest('/checkout/cart');
        $this->assertNull($this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testDoesNotMatchCheckoutCartIndex(): void
    {
        $request = $this->createRequest('/checkout/cart/index');
        $this->assertNull($this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testDoesNotMatchCheckoutPrefixedFrontname(): void
    {
        $request = $this->createRequest('/checkoutaddons/index/index');
        $this->assertNull($this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testDoesNotMatchCheckoutOnepageSuccess(): void
    {
        $request = $this->createRequest('/checkout/onepage/success');
        $this->assertNull($this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testDoesNotMatchDeepPath(): void
    {
        $request = $this->createRequest('/checkout/index/index/extra');
        $this->assertNull($this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testDoesNotMatchNonCheckoutPath(): void
    {
        $request = $this->createRequest('/customer/account/login');
        $this->assertNull($this->getRouter()->match($request));
    }

    #[ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default')]
    public function testDoesNotMatchWhenAccessDenied(): void
    {
        $request = $this->createRequest('/checkout');
        $this->assertNull($this->getRouter()->match($request));
    }
}
