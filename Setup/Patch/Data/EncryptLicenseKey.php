<?php declare(strict_types=1);

namespace LokiCheckout\Core\Setup\Patch\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class EncryptLicenseKey implements DataPatchInterface
{
    private const CONFIG_PATH = 'loki_checkout/general/license_key';

    public function __construct(
        private readonly ResourceConnection $resourceConnection,
        private readonly EncryptorInterface $encryptor,
    ) {
    }

    public static function getDependencies(): array
    {
        return [RenameConfigurationPaths::class];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName('core_config_data');
        $select = $connection->select()
            ->from($table, ['config_id', 'value'])
            ->where('path = ?', self::CONFIG_PATH);

        foreach ($connection->fetchAll($select) as $row) {
            $value = (string)$row['value'];
            if ($value === '' || $this->isEncrypted($value)) {
                continue;
            }

            $connection->update(
                $table,
                ['value' => $this->encryptor->encrypt($value)],
                ['config_id = ?' => (int)$row['config_id']]
            );
        }

        return $this;
    }

    private function isEncrypted(string $value): bool
    {
        return (bool)preg_match('/^\d+:\d+:/', $value);
    }
}
