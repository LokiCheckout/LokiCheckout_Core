<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use LokiCheckout\Core\Plugin\CleanCompositeConfigProviderPlugin;
use Magento\Checkout\Model\CompositeConfigProvider;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CleanCompositeConfigProviderPluginTest extends TestCase
{
    public function testPostCodesAreRemoved(): void
    {
        $subject = ObjectManager::getInstance()->get(CleanCompositeConfigProviderPlugin::class);
        $provider = ObjectManager::getInstance()->get(CompositeConfigProvider::class);

        $config = ['postCodes' => ['NL' => []], 'other' => 'value'];
        $result = $subject->afterGetConfig($provider, $config);

        $this->assertArrayNotHasKey('postCodes', $result);
        $this->assertSame('value', $result['other']);
    }

    public function testConfigWithoutPostCodesIsUnchanged(): void
    {
        $subject = ObjectManager::getInstance()->get(CleanCompositeConfigProviderPlugin::class);
        $provider = ObjectManager::getInstance()->get(CompositeConfigProvider::class);

        $config = ['foo' => 'bar'];
        $result = $subject->afterGetConfig($provider, $config);

        $this->assertSame($config, $result);
    }
}
