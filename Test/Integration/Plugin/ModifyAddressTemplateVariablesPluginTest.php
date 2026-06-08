<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use LokiCheckout\Core\Plugin\ModifyAddressTemplateVariablesPlugin;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Filter\Template;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class ModifyAddressTemplateVariablesPluginTest extends TestCase
{
    private function getSubject(): ModifyAddressTemplateVariablesPlugin
    {
        return ObjectManager::getInstance()->get(ModifyAddressTemplateVariablesPlugin::class);
    }

    private function getTemplate(): Template
    {
        return ObjectManager::getInstance()->get(Template::class);
    }

    #[ConfigFixture('loki_checkout/fields/capitalize_country_in_preview', 1, 'store', 'default')]
    public function testCountryIsCapitalizedWhenEnabled(): void
    {
        $result = $this->getSubject()->beforeSetVariables($this->getTemplate(), ['country' => 'Netherlands']);

        $this->assertSame('NETHERLANDS', $result[0]['country']);
    }

    #[ConfigFixture('loki_checkout/fields/capitalize_country_in_preview', 0, 'store', 'default')]
    public function testCountryUnchangedWhenDisabled(): void
    {
        $result = $this->getSubject()->beforeSetVariables($this->getTemplate(), ['country' => 'Netherlands']);

        $this->assertSame('Netherlands', $result[0]['country']);
    }
}
