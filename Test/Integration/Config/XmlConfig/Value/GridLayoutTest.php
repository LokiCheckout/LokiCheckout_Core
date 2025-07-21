<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Config\XmlConfig\Value;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Config\XmlConfig;

class GridLayoutTest extends TestCase
{
    public function testGetGridLayout()
    {
        $xmlConfig = $this->getObjectManager()->get(XmlConfig::class);
        $gridLayout = $xmlConfig->getGridLayout('default_grid');

        $grid = $gridLayout->getGridByCountryId('BE');
        $this->assertEquals('belgium', $grid->getName());

        $grid = $gridLayout->getGridByCountryId('NL');
        $this->assertEquals('netherlands', $grid->getName());
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
