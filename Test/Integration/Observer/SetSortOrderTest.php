<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\SetSortOrder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class SetSortOrderTest extends TestCase
{
    public function testNoOpWhenBlockIsNotGrid(): void
    {
        $layout = ObjectManager::getInstance()->create(LayoutInterface::class);
        $block = $layout->createBlock(Template::class, 'not.a.grid');

        $this->executeObserver($block);

        $this->assertTrue(true);
    }

    public function testNoOpWhenGridHasSingleChild(): void
    {
        $layout = ObjectManager::getInstance()->create(LayoutInterface::class);
        $parent = $layout->createBlock(Template::class, 'grid.single');
        $parent->setGrid(true);
        $layout->createBlock(Template::class, 'grid.single.child');
        $layout->setChild('grid.single', 'grid.single.child', 'only_child');

        $this->executeObserver($parent);

        $this->assertTrue(true);
    }

    private function executeObserver(Template $block): void
    {
        $event = new Event(['block' => $block]);
        $observer = new Observer(['event' => $event]);

        $subject = ObjectManager::getInstance()->get(SetSortOrder::class);
        $subject->execute($observer);
    }
}
