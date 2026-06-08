<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\AddDebuggingInfo;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Element\Template;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AddDebuggingInfoTest extends TestCase
{
    public function testNonMatchingPrefixIsIgnored(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('some.other.block');

        $original = '<div>content</div>';
        $transport = new DataObject(['html' => $original]);

        $this->executeObserver($block, $transport);

        $this->assertSame($original, $transport->getHtml());
    }

    public function testDebugBlockItselfIsIgnored(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout(AddDebuggingInfo::DEBUG_BLOCK);

        $original = '<div>content</div>';
        $transport = new DataObject(['html' => $original]);

        $this->executeObserver($block, $transport);

        $this->assertSame($original, $transport->getHtml());
    }

    private function executeObserver(Template $block, DataObject $transport): void
    {
        $event = new Event(['block' => $block, 'transport' => $transport]);
        $observer = new Observer(['event' => $event]);

        $subject = ObjectManager::getInstance()->get(AddDebuggingInfo::class);
        $subject->execute($observer);
    }
}
