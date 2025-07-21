<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\Adminhtml\SystemCheck;

use Magento\Framework\App\State as AppState;
use Magento\Framework\Module\Manager as ModuleManager;
use LokiCheckout\Core\ValueObject\Adminhtml\CheckResultCollectionFactory;

class DevModules extends SystemCheck
{
    public function __construct(
        private readonly ModuleManager $moduleManager,
        private readonly AppState $appState,
        CheckResultCollectionFactory $checkResultCollectionFactory
    ) {
        parent::__construct($checkResultCollectionFactory);
    }

    protected function execute(): void
    {
        $isProductionMode = $this->appState->getMode() === AppState::MODE_PRODUCTION;

        $devModules = [
            'LokiCheckout_Bundle',
            'LokiCheckout_DevTools',
            'LokiCheckout_FunctionalTests',
            'LokiCheckout_MollieDevTools',
            'Loki_Debugger',
        ];

        foreach ($devModules as $devModule) {
            $isEnabled = $this->moduleManager->isEnabled($devModule);
            $this->addCheckResult(
                $devModule,
                $isEnabled ? 'Enabled' : 'Disabled',
                !($isProductionMode && $isEnabled),
                'This module should only be enabled in the Developer Mode'
            );
        }
    }
}
