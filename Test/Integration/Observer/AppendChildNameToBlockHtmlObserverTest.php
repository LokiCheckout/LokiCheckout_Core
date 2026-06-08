<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\AppendChildNameToBlockHtmlObserver;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AppendChildNameToBlockHtmlObserverTest extends TestCase
{
    private LayoutInterface $layout;

    protected function setUp(): void
    {
        $this->layout = ObjectManager::getInstance()->create(LayoutInterface::class);
    }

    public function testChildNameAppendedForGridChild(): void
    {
        $parent = $this->layout->createBlock(Template::class, 'parent.grid');
        $parent->setGrid(true);

        $child = $this->layout->createBlock(Template::class, 'child.block');
        $this->layout->setChild('parent.grid', 'child.block', 'my_child_alias');

        $transport = new DataObject(['html' => '<div class="child">content</div>']);

        $this->executeObserver($child, $transport);

        $this->assertStringContainsString('data-child-name="my_child_alias"', $transport->getHtml());
    }

    public function testNonGridParentIsIgnored(): void
    {
        $parent = $this->layout->createBlock(Template::class, 'parent.plain');

        $child = $this->layout->createBlock(Template::class, 'child.plain');
        $this->layout->setChild('parent.plain', 'child.plain', 'plain_alias');

        $original = '<div class="child">content</div>';
        $transport = new DataObject(['html' => $original]);

        $this->executeObserver($child, $transport);

        $this->assertSame($original, $transport->getHtml());
    }

    private function executeObserver(Template $block, DataObject $transport): void
    {
        $event = new Event(['block' => $block, 'transport' => $transport]);
        $observer = new Observer(['event' => $event]);

        $subject = new AppendChildNameToBlockHtmlObserver($this->layout);
        $subject->execute($observer);
    }
}
