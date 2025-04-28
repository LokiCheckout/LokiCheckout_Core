<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Module\ModuleList\Loader as ModuleListLoader;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;
use Yireo\LokiCheckout\Util\Composer\ComposerPackageFactory;
use Yireo\LokiCheckout\ValueObject\Adminhtml\Module;
use Yireo\LokiCheckout\ValueObject\Adminhtml\ModuleFactory;

class Modules implements ArgumentInterface
{
    public function __construct(
        private readonly ModuleListLoader $moduleListLoader,
        private readonly ComposerPackageFactory $composerPackageFactory,
        private readonly LokiCheckoutModules $lokiCheckoutModules,
        private readonly ModuleFactory $moduleFactory,
    ) {
    }

    /**
     * @return Module[]
     * @throws LocalizedException
     */
    public function getModules(): array
    {
        $modules = [];
        foreach ($this->moduleListLoader->load() as $module) {
            $moduleName = $module['name'];
            $isLokiCheckoutRegistered = $this->lokiCheckoutModules->hasModule($moduleName);
            if (false === str_contains($moduleName, 'LokiCheckout') && false === $isLokiCheckoutRegistered) {
                continue;
            }

            $composerPackage = $this->composerPackageFactory->createByModuleName($moduleName);
            $modules[] = $this->moduleFactory->create([
                'moduleName' => $moduleName,
                'composerPackage' => $composerPackage,
            ]);
        }

        return $modules;
    }

    public function getLatestModuleVersion(Module $module): string
    {
        if ($module->isDevVersion()) {
            if ($module->getLatestCommit() !== '' && $module->getLatestCommit() !== '0') {
                return $this->shortenGitCommit($module->getLatestCommit());
            }

            return 'unknown';
        }

        if (!in_array($module->getLatestComposerVersion(), ['', '0'], true)) {
            return $module->getLatestComposerVersion();
        }

        return 'unknown';
    }

    public function getLatestModuleVersionClass(Module $module): string
    {
        if ($module->isDevVersion()) {
            if ($module->getGitInfo()->getCommit() === $module->getLatestCommit()) {
                return 'notice';
            }

            return 'warning';
        }

        if ($module->getComposerVersion() === $module->getLatestComposerVersion()) {
            return 'notice';
        }

        return 'error';
    }

    public function shortenGitCommit(string $gitCommit): string
    {
        return substr($gitCommit, strlen($gitCommit) - 8, 8);
    }
}
