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
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\ComponentRegistry;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\MagewireTestUtils\Test\Integration\MagewireComponentTestCase;

class LokiTestCase extends MagewireComponentTestCase
{
    protected ?ObjectManagerInterface $objectManager = null;
    protected ?DataFixtureStorage $fixtures = null;
    protected ?ComponentRegistry $componentRegistry = null;

    use CartFixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->objectManager = Bootstrap::getObjectManager();
        $this->fixtures = DataFixtureStorageManager::getStorage();
        $this->componentRegistry = $this->objectManager->get(ComponentRegistry::class);
    }

    public function dispatchToCheckout(CartInterface $cart)
    {
        $this->assertTrue($cart->getItemsCount() > 0);
        $this->dispatch('checkout/index/index');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertNotEmpty($response->getBody(), 'No body');
    }

    public function getComponentByBlockName(string $blockName): Component
    {
        $layout = $this->_objectManager->get(LayoutInterface::class);
        $block = $layout->getBlock($blockName);
        $this->assertInstanceOf(Template::class, $block);

        $magewire = $block->getMagewire();
        $this->assertInstanceOf(Component::class, $block);
        return $magewire;
    }

    protected function getCart(): CartInterface
    {
        $cart = $this->fixtures->get('cart');
        $this->assertInstanceOf(CartInterface::class, $cart);

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cart->getId());

        return $cart;
    }

    public function assertMagewireComponentExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        $this->assertMagewireBlockExistsInHtml($blockName, $body);

        // @todo: Enable elsewhere
        //$this->assertMagewireComponentResolves($blockName, $component, ['checkout_index_index']);
    }

    public function assertMagewireComponentNotExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        $this->assertStringNotContainsString('wire:id="'.$blockName.'"', $body);
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
