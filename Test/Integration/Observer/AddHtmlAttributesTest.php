<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\AddHtmlAttributes;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Element\Template;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AddHtmlAttributesTest extends TestCase
{
    public function testHtmlAttributesArePushedOntoOutput(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('loki-checkout.test.block');
        $block->setData('html_attributes', ['data-test' => 'value']);

        $transport = new DataObject(['html' => '<div class="wrapper">content</div>']);

        $this->executeObserver($block, $transport);

        $this->assertStringContainsString('data-test="value"', $transport->getHtml());
    }

    public function testNonLokiBlockIsIgnored(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('other.block');
        $block->setData('html_attributes', ['data-test' => 'value']);

        $original = '<div>content</div>';
        $transport = new DataObject(['html' => $original]);

        $this->executeObserver($block, $transport);

        $this->assertSame($original, $transport->getHtml());
    }

    private function executeObserver(Template $block, DataObject $transport): void
    {
        $event = new Event(['block' => $block, 'transport' => $transport]);
        $observer = new Observer(['event' => $event]);

        $subject = ObjectManager::getInstance()->get(AddHtmlAttributes::class);
        $subject->execute($observer);
    }
}
