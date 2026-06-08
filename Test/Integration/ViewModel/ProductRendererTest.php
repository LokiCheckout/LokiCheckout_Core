<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\ProductRenderer;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class ProductRendererTest extends TestCase
{
    private function createSubjectWithEmptyLayout(): ProductRenderer
    {
        $layout = ObjectManager::getInstance()->create(LayoutInterface::class);
        $configuration = ObjectManager::getInstance()->get(Configuration::class);

        return new ProductRenderer($layout, $configuration);
    }

    public function testGetRendererReturnsNullWhenRendererListMissing(): void
    {
        $subject = $this->createSubjectWithEmptyLayout();
        $item = $this->createMock(CartItemInterface::class);
        $item->method('getProductType')->willReturn('simple');

        $this->assertNull($subject->getRenderer($item));
    }

    public function testGetImageReturnsUnknownWhenNoRenderer(): void
    {
        $subject = $this->createSubjectWithEmptyLayout();
        $item = $this->createMock(CartItemInterface::class);
        $item->method('getProductType')->willReturn('simple');

        $this->assertSame('unknown', $subject->getImage($item));
    }
}
