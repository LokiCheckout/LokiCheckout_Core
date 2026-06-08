<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use Loki\Components\Component\Component;
use LokiCheckout\Core\Plugin\AppendCheckoutTargetsPlugin;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AppendCheckoutTargetsPluginTest extends TestCase
{
    private AppendCheckoutTargetsPlugin $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(AppendCheckoutTargetsPlugin::class);
    }

    public function testPrivateDataTargetAppendedForCheckoutComponent(): void
    {
        $component = $this->createMock(Component::class);
        $component->method('getName')->willReturn('loki-checkout.shipping.address');

        $result = $this->subject->afterGetTargets($component, ['existing.target']);

        $this->assertContains('loki-checkout.utils.private-data', $result);
        $this->assertContains('existing.target', $result);
    }

    public function testNoTargetAppendedForNonCheckoutComponent(): void
    {
        $component = $this->createMock(Component::class);
        $component->method('getName')->willReturn('some-other.component');

        $result = $this->subject->afterGetTargets($component, ['existing.target']);

        $this->assertNotContains('loki-checkout.utils.private-data', $result);
        $this->assertSame(['existing.target'], $result);
    }
}
