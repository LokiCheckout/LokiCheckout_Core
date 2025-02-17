<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml;

use Magento\Framework\Module\ModuleList;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Modules implements ArgumentInterface
{
    public function __construct(
        private ModuleList $moduleList
    ) {
    }

    public function getModules(): array
    {
        $modules = [];
        foreach ($this->moduleList->getAll() as $module) {
            $modules[] = $module;
        }

        return $modules;
    }
}
