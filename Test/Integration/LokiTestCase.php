<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Firstname;
use Yireo\LokiCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\MagewireTestUtils\Test\Integration\MagewireComponentTestCase;

class LokiTestCase extends MagewireComponentTestCase
{
    use ProductFixture;

    public function dispatchToCheckout()
    {
        $this->dispatch('checkout/index/index');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertNotEmpty($response->getBody());
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

    protected function getCart()
    {
        $fixtures = DataFixtureStorageManager::getStorage();
        return $fixtures->get('cart');
    }

    public function assertMagewireComponentExistsOnPage(string $blockName, Component $component)
    {
        $body = $this->getResponse()->getBody();
        $this->assertMagewireBlockExistsInHtml($blockName, $body);
        $this->assertMagewireComponentResolves($blockName, $component, ['checkout_index_index']);
    }

    public function assertMagewireComponentNotExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        $this->assertStringNotContainsString('wire:id="'.$blockName.'"', $body);
    }
}
