<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\Module\Manager as ModuleManager;

class LokiCheckoutModules
{
    public function __construct(
        private ModuleManager $moduleManager,
        private array $modules = []
    ) {
    }

    /**
     * @return string[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    public function getActiveModules(): array
    {
        $moduleManager = $this->moduleManager;

        return array_filter($this->getModules(), function (string $module) use ($moduleManager) {
            return $moduleManager->isEnabled($module);
        });
    }
}
