<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Setup\Patch\Data;

use LokiCheckout\Core\Setup\Patch\Data\RenameConfigurationPaths;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

#[
    DbIsolation(true),
    AppArea('adminhtml'),
]
class RenameConfigurationPathsTest extends TestCase
{
    public function testApplyRenamesLegacyPaths(): void
    {
        $resourceConnection = ObjectManager::getInstance()->get(ResourceConnection::class);
        $connection = $resourceConnection->getConnection();
        $table = $resourceConnection->getTableName('core_config_data');

        $connection->insert($table, [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'yireo_loki_checkout/general/enable',
            'value' => '1',
        ]);

        $subject = ObjectManager::getInstance()->get(RenameConfigurationPaths::class);
        $subject->apply();

        $oldExists = $connection->fetchOne(
            $connection->select()->from($table, 'path')->where('path = ?', 'yireo_loki_checkout/general/enable')
        );
        $this->assertFalse($oldExists);

        $newValue = $connection->fetchOne(
            $connection->select()->from($table, 'value')->where('path = ?', 'loki_checkout/general/enable')
        );
        $this->assertSame('1', $newValue);
    }

    public function testApplyIsNoOpWhenNoLegacyPaths(): void
    {
        $subject = ObjectManager::getInstance()->get(RenameConfigurationPaths::class);
        $this->assertSame($subject, $subject->apply());
    }
}
