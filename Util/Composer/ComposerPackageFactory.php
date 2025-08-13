<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Composer;

use Magento\Framework\Component\ComponentRegistrar;
use RuntimeException;

class ComposerPackageFactory
{
    public function __construct(
        private readonly ComponentRegistrar $componentRegistrar,
        private readonly ComposerLock $composerLock,
    ) {
    }

    public function createByModuleName(string $moduleName): ComposerPackage
    {
        $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
        try {
            $composerFile = $this->getComposerFileFromPath($modulePath);
        } catch (RuntimeException $e) {
            return new ComposerPackage(
                $moduleName,
                '',
                '',
                ''
            );
        }

        $composerData = json_decode(file_get_contents($composerFile), true);
        $composerName = $composerData['name'];
        $composerVersion = $this->composerLock->getComposerVersion($composerName);
        $composerDate = $this->composerLock->getComposerDate($composerName);

        return new ComposerPackage(
            $moduleName,
            $composerName,
            $composerVersion,
            $composerDate
        );
    }

    private function getComposerFileFromPath(string $path): string
    {
        if (is_file($path . '/composer.json')) {
            return $path . '/composer.json';
        }

        if (is_file($path . '/../composer.json')) {
            return $path . '/../composer.json';
        }

        if (is_file($path . '/../../composer.json')) {
            return $path . '/../../composer.json';
        }

        throw new RuntimeException('Could not find composer.json for path "' . $path . '"');
    }
}
