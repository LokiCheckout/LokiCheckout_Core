<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Integration;

use Magento\Framework\App\Response\Http;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magewirephp\Magewire\Component;
use Yireo\MagewireTestUtils\Test\Integration\MagewireComponentTestCase;
use Magento\Indexer\Model\ResourceModel\Indexer\State as IndexerStateResourceModel;

class FriggaTestCase extends MagewireComponentTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //$this->fixtures = $this->_objectManager->get(DataFixtureStorageManager::class)->getStorage();

        //$cache = ObjectManager::getInstance()->get(\Magento\Framework\App\Cache::class);
        //$cache->clean();
        // select * from indexer_state

        /*
        $this->_objectManager->addSharedInstance($this->createMock(\Magento\CatalogInventory\Observer\SaveInventoryDataObserver::class), \Magento\CatalogInventory\Observer\SaveInventoryDataObserver::class);
        */

    }

    public function dispatchToCart()
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
}
