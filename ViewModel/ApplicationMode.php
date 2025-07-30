<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\App\State as AppState;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ApplicationMode implements ArgumentInterface
{
    public function __construct(
        private AppState $appState,
    ) {
    }

    public function isDeveloperMode(): bool
    {
        return $this->appState->getMode() === AppState::MODE_DEVELOPER;
    }
}
