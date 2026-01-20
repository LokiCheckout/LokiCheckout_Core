<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Config\XmlConfig\Value\GridLayout;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Fixture\Cache;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use LokiCheckout\Core\Config\XmlConfig;

class GridTest extends TestCase
{
    /**
     * @return void
     * @dataProvider getTestGridBlockArguments
     */
    #[Cache('config', false)]
    public function testGridBlock(string $gridName, string $blockAlias, int $colSpan, int $mdColspan)
    {
        $xmlConfig = $this->getObjectManager()->get(XmlConfig::class);
        $gridLayout = $xmlConfig->getGridLayout('default_grid');

        $grid = $gridLayout->getGridByName($gridName);
        $block = $grid->getBlock($blockAlias);
        $this->assertEquals($colSpan, $block->getColSpan());
        $this->assertEquals($mdColspan, $block->getMdColSpan());
    }

    public function getTestGridBlockArguments(): array
    {
        return [
            ['default', 'city', 12, 6],
            ['default', 'country_id', 12, 6],
        ];
    }

    /**
     * @return ObjectManagerInterface
     * @SuppressWarnings StaticAccess
     */
    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
