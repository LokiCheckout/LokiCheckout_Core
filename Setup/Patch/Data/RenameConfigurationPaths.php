<?php declare(strict_types=1);

namespace LokiCheckout\Core\Setup\Patch\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class RenameConfigurationPaths implements DataPatchInterface
{
    public function __construct(
        private ResourceConnection $resourceConnection
    ) {
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName('core_config_data');
        $select = $connection->select()->from($table)->where('path LIKE ?', 'yireo\_loki\_checkout/%');
        $rows = $connection->fetchAll($select);
        if (empty($rows)) {
            return $this;
        }

        foreach ($rows as $row) {
            $oldPath = $row['path'];
            $newPath = str_replace('yireo_loki_checkout/', 'loki_checkout/', $oldPath);
            $connection->update($table, ['path' => $newPath], ['config_id = ?' => (int)$row['config_id']]);
        }

        return $this;
    }
}
