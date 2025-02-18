<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml;

use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;
use Yireo\LokiCheckout\Util\Composer\ComposerApi;
use Yireo\LokiCheckout\Util\Composer\ComposerPackageFactory;
use Yireo\LokiCheckout\ValueObject\Adminhtml\Module;

class Modules implements ArgumentInterface
{
    public function __construct(
        private ModuleList\Loader $moduleListLoader,
        private ModuleManager $moduleManager,
        private LokiCheckoutModules $lokiCheckoutModules,
        private ComposerApi $composerApi,
        private ComposerPackageFactory $composerPackageFactory,
    ) {
    }

    /**
     * @return Module[]
     */
    public function getModules(): array
    {
        $modules = [];
        foreach ($this->moduleListLoader->load() as $module) {
            $isLokiCheckoutRegistered = $this->lokiCheckoutModules->hasModule($module['name']);
            if (!strstr($module['name'], 'LokiCheckout') && !$isLokiCheckoutRegistered) {
                continue;
            }

            $composerPackage = $this->composerPackageFactory->createByModuleName($module['name']);
            $modules[] = new Module(
                $module['name'],
                $this->moduleManager->isEnabled($module['name']),
                $isLokiCheckoutRegistered,
                $composerPackage->getComposerName(),
                $composerPackage->getComposerVersion(),
                $this->composerApi->getAvailableVersion($composerPackage->getComposerName()),
            );
        }

        return $modules;
    }
}
