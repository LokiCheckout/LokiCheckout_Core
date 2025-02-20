<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ValueObject\Adminhtml;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\Manager as ModuleManager;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;
use Yireo\LokiCheckout\Util\Composer\ComposerApi;
use Yireo\LokiCheckout\Util\Composer\ComposerPackage;

class Module
{
    public function __construct(
        private ComponentRegistrar $componentRegistrar,
        private ModuleManager $moduleManager,
        private LokiCheckoutModules $lokiCheckoutModules,
        private GitInfoFactory $gitInfoFactory,
        private ComposerApi $composerApi,
        private string $moduleName,
        private ComposerPackage $composerPackage,
    ) {
    }

    public function getName(): string
    {
        return $this->moduleName;
    }

    public function isEnabled(): bool
    {
        return $this->moduleManager->isEnabled($this->getName());
    }

    public function isLokiCheckoutRegistered(): bool
    {
        return $this->lokiCheckoutModules->hasModule($this->getName());
    }

    public function getComposerName(): string
    {
        return $this->composerPackage->getComposerName();
    }

    public function getComposerVersion(): string
    {
        return $this->composerPackage->getComposerVersion();
    }

    public function getLatestCommit(): string
    {
        return $this->composerApi->getLatestCommit($this->composerPackage->getComposerName());
    }

    public function getLatestComposerVersion(): string
    {
        return $this->composerApi->getAvailableVersion($this->composerPackage->getComposerName());
    }

    public function getModulePath(): string
    {
        return $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $this->getName());
    }

    public function isDevVersion(): bool
    {
        return $this->getGitInfo()->hasInfo();
    }

    public function getGitInfo(): GitInfo
    {
        return $this->gitInfoFactory->create([
            'path' => $this->getModulePath(),
        ]);
    }
}
