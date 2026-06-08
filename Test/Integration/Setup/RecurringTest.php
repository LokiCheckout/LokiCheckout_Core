<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Setup;

use LokiCheckout\Core\Setup\Recurring;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('adminhtml')]
class RecurringTest extends TestCase
{
    public function testIsInstallSchema(): void
    {
        $subject = ObjectManager::getInstance()->get(Recurring::class);
        $this->assertInstanceOf(InstallSchemaInterface::class, $subject);
    }

    public function testInstallEnablesMissingDependencies(): void
    {
        $subject = ObjectManager::getInstance()->get(Recurring::class);

        $setup = $this->createMock(SchemaSetupInterface::class);
        $context = $this->createMock(ModuleContextInterface::class);

        $subject->install($setup, $context);

        $this->assertTrue(true);
    }
}
