<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Response\Http;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\TestFramework\Fixture\DataFixtureStorage;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;

use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertStoreConfigValueEquals;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;

class LokiTestCase extends TestCase
{
    protected ?ObjectManagerInterface $objectManager = null;
    protected ?DataFixtureStorage $fixtures = null;
    protected ?ComponentRegistry $componentRegistry = null;

    use CartFixture;
    use AssertStoreConfigValueEquals;

    protected function setUp(): void
    {
        parent::setUp();

        $this->objectManager = Bootstrap::getObjectManager();
        $this->fixtures = DataFixtureStorageManager::getStorage();
        $this->componentRegistry = $this->objectManager->get(ComponentRegistry::class);

        $this->assertModuleIsEnabled('Yireo_LokiCheckout');
        $this->assertStoreConfigValueEquals(1, 'yireo_loki_checkout/general/enable');
    }

    public function dispatchToCheckout(CartInterface $cart)
    {
        $this->assertTrue($cart->getItemsCount() > 0);
        $this->getObjectManager()->get(LayoutInterface::class)->getUpdate()->addHandle([
            'loki_checkout',
            'loki_checkout_theme_default',
        ]);

        $this->dispatch('checkout/index/index');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertNotEmpty($response->getBody(), 'No body');
    }

    public function getComponentByBlockName(string $blockName): ComponentInterface
    {
        $layout = $this->getObjectManager()->get(LayoutInterface::class);
        $block = $layout->getBlock($blockName);
        $this->assertInstanceOf(Template::class, $block);

        $component = $block->getData('component');
        $this->assertInstanceOf(ComponentInterface::class, $component);
        return $component;
    }

    protected function getCart(): CartInterface
    {
        $cart = $this->fixtures->get('cart');
        $this->assertInstanceOf(CartInterface::class, $cart);

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cart->getId());

        return $cart;
    }

    public function assertComponentExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        // @todo
    }

    public function assertComponentNotExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        $this->assertStringNotContainsString('x-title="'.$blockName.'"', $body);
    }

    protected function addDummyBlockToComponent(Component $component): Component
    {
        $block = $this->getObjectManager()->create(Template::class);
        $component->setParent($block);
        return $component;
    }

    protected function getDummyComponentInstance(string $componentClass): Component
    {
        $component = $this->getObjectManager()->get($componentClass);
        $this->addDummyBlockToComponent($component);
        return $component;
    }
}
