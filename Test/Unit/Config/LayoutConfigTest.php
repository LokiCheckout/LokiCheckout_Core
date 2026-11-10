<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Config;

use LokiCheckout\Core\Config\LayoutConfig;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\TestCase;

class LayoutConfigTest extends TestCase
{
    public function testWithoutLoadedLayout(): void
    {
        $layoutConfig = new LayoutConfig();
        $this->assertFalse($layoutConfig->has('foo/bar/baz'));
        $this->assertNull($layoutConfig->getValue('foo/bar/baz'));
    }

    public function testWithoutConfigBlock(): void
    {
        $layout = $this->createMock(LayoutInterface::class);
        $layout->method('getBlock')->with(LayoutConfig::BLOCK_NAME)->willReturn(false);

        $layoutConfig = new LayoutConfig();
        $layoutConfig->loadFromLayout($layout);
        $this->assertFalse($layoutConfig->has('foo/bar/baz'));
    }

    public function testWithConfigBlock(): void
    {
        $layoutConfig = new LayoutConfig();
        $layoutConfig->loadFromLayout($this->getLayout(['foo/bar/baz' => 'qux', 'foo/bar/off' => false]));

        $this->assertTrue($layoutConfig->has('foo/bar/baz'));
        $this->assertSame('qux', $layoutConfig->getValue('foo/bar/baz'));
        $this->assertTrue($layoutConfig->has('foo/bar/off'));
        $this->assertFalse($layoutConfig->getValue('foo/bar/off'));
        $this->assertFalse($layoutConfig->has('foo/bar/unknown'));
    }

    public function testLayoutWithoutConfigBlockKeepsPreviousValues(): void
    {
        $layoutConfig = new LayoutConfig();
        $layoutConfig->loadFromLayout($this->getLayout(['foo/bar/baz' => 'qux']));

        $emptyLayout = $this->createMock(LayoutInterface::class);
        $emptyLayout->method('getBlock')->willReturn(false);
        $layoutConfig->loadFromLayout($emptyLayout);

        $this->assertSame('qux', $layoutConfig->getValue('foo/bar/baz'));
    }

    private function getLayout(array $config): LayoutInterface
    {
        $block = $this->getMockBuilder(AbstractBlock::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getData'])
            ->getMock();
        $block->method('getData')->with('config')->willReturn($config);

        $layout = $this->createMock(LayoutInterface::class);
        $layout->method('getBlock')->with(LayoutConfig::BLOCK_NAME)->willReturn($block);

        return $layout;
    }
}
