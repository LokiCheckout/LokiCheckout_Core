<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Block;

use LokiCheckout\Core\Util\Block\SortedBlocks;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class SortedBlocksTest extends TestCase
{
    public function testGetChildrenSortsByGridSortOrder(): void
    {
        $layout = ObjectManager::getInstance()->get(LayoutInterface::class);
        $parentBlock = $layout->createBlock(Template::class, 'sorted-blocks-test.parent');
        $parentBlock->setGridLayout('default_grid');

        foreach (['lastname', 'firstname', 'prefix'] as $alias) {
            $layout->createBlock(Template::class, 'sorted-blocks-test.' . $alias);
            $layout->setChild('sorted-blocks-test.parent', 'sorted-blocks-test.' . $alias, $alias);
        }

        $children = ObjectManager::getInstance()->get(SortedBlocks::class)->getChildren($parentBlock);

        $this->assertSame(
            ['sorted-blocks-test.prefix', 'sorted-blocks-test.firstname', 'sorted-blocks-test.lastname'],
            array_map(fn (AbstractBlock $block) => $block->getNameInLayout(), $children)
        );
    }
}
