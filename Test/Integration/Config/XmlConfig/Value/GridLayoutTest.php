<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Config\XmlConfig\Value;

use LokiCheckout\Core\Exception\GridNotFoundException;
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

        $this->expectException(GridNotFoundException::class);
        $gridLayout->getGridByCountryId('BE');
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
