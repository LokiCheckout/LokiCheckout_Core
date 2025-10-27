<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\Manager as ModuleManager;

class LokiCheckoutModules
{
    public function __construct(
        private readonly ModuleManager $moduleManager,
        private readonly ComponentRegistrar $componentRegistrar,
        private array $modules = []
    ) {
    }

    /**
     * @return string[]
     */
    public function getModules(): array
    {
        $paths = $this->componentRegistrar->getPaths(ComponentRegistrar::MODULE);
        foreach ($paths as $moduleName => $modulePath) {
            if (str_contains($moduleName, 'LokiCheckout') && false === $this->hasModule($moduleName)) {
                $this->modules[] = $moduleName;
            }
        }

        return $this->modules;
    }

    public function getActiveModules(): array
    {
        $moduleManager = $this->moduleManager;

        return array_filter($this->getModules(), function (string $module) use ($moduleManager) {
            return $moduleManager->isEnabled($module);
        });
    }

    public function hasModule(string $moduleName): bool
    {
        return in_array($moduleName, $this->modules, true);
    }

    public function seemsModule(string $moduleName):bool
    {
        if ($this->hasModule($moduleName)) {
            return true;
        }

        return str_contains($moduleName, 'LokiCheckout');
    }
}
