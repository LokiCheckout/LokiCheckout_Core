<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Config\XmlConfig\Value\GridLayout;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yireo\LokiCheckout\Config\XmlConfig;

class GridTest extends TestCase
{
    /**
     * @return void
     * @dataProvider getTestGridBlockArguments
     */
    public function testGridBlock(string $gridName, string $blockAlias, int $colSpan, int $mdColspan)
    {
        $xmlConfig = $this->getObjectManager()->get(XmlConfig::class);
        $gridLayout = $xmlConfig->getGridLayout('default_grid');
        $grid = $gridLayout->getGridByName($gridName);

        $block = $grid->getBlock('city');
        $this->assertEquals($colSpan, $block->getColSpan());
        $this->assertEquals($mdColspan, $block->getMdColSpan());
    }

    public function getTestGridBlockArguments(): array
    {
        return [
            ['NL', 'city', 12, 6],
            ['default', 'country_id', 12, 6],
            ['NL', 'country_id', 12, 12],
        ];
    }

    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
