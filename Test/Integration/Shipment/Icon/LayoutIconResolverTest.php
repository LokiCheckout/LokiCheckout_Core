<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Shipment\Icon;

use Loki\Components\Util\Block\ImageRenderer;
use LokiCheckout\Core\Shipment\Icon\IconResolverContext;
use LokiCheckout\Core\Shipment\Icon\LayoutIconResolver;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class LayoutIconResolverTest extends TestCase
{
    public function testResolveReturnsFalseWhenBlockMissing(): void
    {
        $layout = $this->createMock(LayoutInterface::class);
        $layout->method('getBlock')->willReturn(false);

        $imageRenderer = ObjectManager::getInstance()->get(ImageRenderer::class);
        $subject = new LayoutIconResolver($layout, $imageRenderer);

        $context = $this->createContext('flatrate_flatrate');
        $this->assertFalse($subject->resolve($context));
    }

    public function testResolveReturnsFalseWhenBlockHasNoIcon(): void
    {
        $block = $this->getMockBuilder(AbstractBlock::class)
            ->disableOriginalConstructor()
            ->addMethods(['getIcon'])
            ->getMockForAbstractClass();
        $block->method('getIcon')->willReturn('');

        $layout = $this->createMock(LayoutInterface::class);
        $layout->method('getBlock')->willReturn($block);

        $imageRenderer = ObjectManager::getInstance()->get(ImageRenderer::class);
        $subject = new LayoutIconResolver($layout, $imageRenderer);

        $context = $this->createContext('flatrate_flatrate');
        $this->assertFalse($subject->resolve($context));
    }

    private function createContext(string $code): IconResolverContext
    {
        return ObjectManager::getInstance()->create(IconResolverContext::class, [
            'shipmentMethodCode' => $code,
            'attributes' => [],
        ]);
    }
}
