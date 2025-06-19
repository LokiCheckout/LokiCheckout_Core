<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Config;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yireo\LokiCheckout\Config\XmlConfig;

class XmlConfigTest extends TestCase
{
    public function testGetGridLayout()
    {
        $xmlConfig = $this->getObjectManager()->get(XmlConfig::class);
        $gridLayout = $xmlConfig->getGridLayout('default_grid');
        $this->assertEquals('default_grid', $gridLayout->getName());
    }

    public function testGetWrongGridLayout()
    {
        $this->expectException(RuntimeException::class);
        $xmlConfig = $this->getObjectManager()->get(XmlConfig::class);
        $xmlConfig->getGridLayout('non_existing');
    }

    private function getObjectManager(): ObjectManagerInterface
    {
        return ObjectManager::getInstance();
    }
}
