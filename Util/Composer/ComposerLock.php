<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Composer;

use Magento\Framework\Filesystem\DirectoryList;

class ComposerLock
{
    public function __construct(
        private DirectoryList $directoryList
    ) {
    }

    public function getComposerLockData(): array
    {
        static $composerLockData = null;
        if (is_array($composerLockData)) {
            return $composerLockData;
        }

        $composerLockFile = $this->directoryList->getRoot().'/composer.lock';
        $composerLockData = json_decode(file_get_contents($composerLockFile), true);

        return $composerLockData;
    }

    public function getComposerVersion(string $composerName): string
    {
        $data = $this->getComposerLockData();
        if (!isset($data['packages'])) {
            return '';
        }

        foreach ($data['packages'] as $package) {
            if ($package['name'] === $composerName) {
                return $package['version'];
            }
        }

        return 'unknown';
    }
}
