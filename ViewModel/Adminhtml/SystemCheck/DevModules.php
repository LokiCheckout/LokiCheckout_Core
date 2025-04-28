<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Magento\Framework\App\State as AppState;
use Magento\Framework\Module\Manager as ModuleManager;
use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResultCollectionFactory;

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
            'Yireo_LokiCheckoutBundle',
            'Yireo_LokiCheckoutDevTools',
            'Yireo_LokiCheckoutFunctionalTests',
            'Yireo_LokiCheckoutMollieDevTools',
            'Yireo_LokiComponentsDebugger',
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
