<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use LokiCheckout\Core\Plugin\AddCspInlineScriptsPlugin;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AddCspInlineScriptsPluginTest extends TestCase
{
    private AddCspInlineScriptsPlugin $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(AddCspInlineScriptsPlugin::class);
    }

    public function testNonLokiBlockHtmlIsUnchanged(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('catalog.product.view');

        $html = '<script>console.log(1)</script>';
        $result = $this->subject->afterToHtml($block, $html);

        $this->assertSame($html, $result);
    }

    public function testLokiBlockHtmlIsProcessed(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('loki-checkout.test');

        $html = '<div>no scripts here</div>';
        $result = $this->subject->afterToHtml($block, $html);

        $this->assertIsString($result);
        $this->assertStringContainsString('no scripts here', $result);
    }
}
