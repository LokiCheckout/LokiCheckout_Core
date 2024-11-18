<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Util\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Util\Block\CssClass;

class CssClassTest extends TestCase
{
    public function testWithBasicInput()
    {
        $cssClass = new CssClass();
        $cssClass->setBlock($this->getBlock());

        $this->assertEquals('class1 class2', $cssClass('class1 class2'));
        //$this->assertEquals('class1 class1', $cssClass('class1'));
    }

    public function testWithBlockConfiguration()
    {
        $block = $this->getBlock();
        $block->setCssClasses(['block' => ['additional' => 'class2']]);

        $cssClass = new CssClass();
        $cssClass->setBlock($block);
        $this->assertEquals('class1 class2', $cssClass('class1'));
    }

    public function testWithOtherConfiguration()
    {
        $block = $this->getBlock();
        $block->setCssClasses(['other' => ['additional' => 'class2']]);

        $cssClass = new CssClass();
        $cssClass->setBlock($block);
        $this->assertEquals('class1 class2', $cssClass('class1', 'other'));
    }

    private function getBlock(): Template|MockObject
    {
        $layout = $this->createMock(LayoutInterface::class);
        $context = $this->createMock(Context::class);
        $context->method('getLayout')->willReturn($layout);

        $block = new Template($context);
        return $block;
    }
}
