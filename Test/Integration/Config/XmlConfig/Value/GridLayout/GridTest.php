<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Config\XmlConfig\Value\GridLayout;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Fixture\Cache;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yireo\LokiCheckout\Config\XmlConfig;

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
        $this->assertEquals($gridName, $grid->getName());

        $block = $grid->getBlock($blockAlias);
        $this->assertEquals($colSpan, $block->getColSpan());
        $this->assertEquals($mdColspan, $block->getMdColSpan());
    }

    public function getTestGridBlockArguments(): array
    {
        return [
            ['netherlands', 'city', 12, 6],
            ['belgium', 'city', 12, 9],
            ['default', 'country_id', 12, 6],
            ['netherlands', 'country_id', 12, 12],
        ];
    }

    /**
     * @return ObjectManagerInterface
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
