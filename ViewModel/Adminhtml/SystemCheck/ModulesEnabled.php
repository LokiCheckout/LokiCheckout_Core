<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Filesystem\Driver\File as FilesystemDriver;
use Magento\Framework\Module\Declaration\Converter\Dom as DomConvertor;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\Xml\ParserFactory;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;
use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResultCollectionFactory;

class ModulesEnabled extends SystemCheck
{
    public function __construct(
        private ComponentRegistrar $componentRegistrar,
        private ModuleList $moduleList,
        private ModuleManager $moduleManager,
        private FilesystemDriver $filesystemDriver,
        private ParserFactory $parserFactory,
        private DomConvertor $domConvertor,
        private LokiCheckoutModules $lokiCheckoutModules,
        CheckResultCollectionFactory $checkResultCollectionFactory
    ) {
        parent::__construct($checkResultCollectionFactory);
    }

    protected function execute(): void
    {
        $moduleDependencies = [];

        foreach ($this->moduleList->getAll() as $module) {
            $moduleName = $module['name'];
            if (false === $this->lokiCheckoutModules->seemsModule($moduleName)) {
                continue;
            }

            $moduleConfig = $this->getModuleConfig($module['name']);
            if (!isset($moduleConfig[$moduleName]['sequence'])) {
                continue;
            }

            foreach ($moduleConfig[$moduleName]['sequence'] as $moduleDependency) {
                if (isset($moduleDependencies[$moduleDependency])) {
                    $moduleDependencies[$moduleDependency][] = $moduleName;
                    continue;
                }

                $moduleDependencies[$moduleDependency] = [$moduleName];
            }
        }

        foreach ($moduleDependencies as $moduleDependency => $moduleNames) {
            $isEnabled = $this->moduleManager->isEnabled($moduleDependency);
            $this->addCheckResult(
                $moduleDependency,
                $isEnabled ? 'Enabled' : 'Disabled',
                $isEnabled,
                (string)__('The following modules depend on module %1 to be enabled', $moduleDependency).': '. implode(', ', $moduleNames)
            );
        }
    }

    private function getModuleConfig(string $moduleName): array
    {
        $moduleFolder = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
        $moduleXml = $moduleFolder.'/etc/module.xml';
        $contents = $this->filesystemDriver->fileGetContents($moduleXml);

        $parser = $this->parserFactory->create();
        $parser->initErrorHandler();
        $parser->loadXML($contents);
        $data = $this->domConvertor->convert($parser->getDom());
        return $data;
    }
}
