<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Util\CurrentTheme;
use Magento\Framework\View\LayoutInterface;

class AddLayoutHandles
{
    public function __construct(
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private readonly CurrentTheme $currentTheme
    ) {
    }

    public function execute(): void
    {
        $this->addGenericHandles();
        $this->addThemeHandles();
    }

    private function addGenericHandles(): void
    {
        $theme = $this->config->getTheme();
        $this->layout->getUpdate()->addHandle('loki_checkout');
        $this->layout->getUpdate()->addHandle('loki_checkout_theme_' . $theme);
    }

    private function addThemeHandles(): void
    {
        if ($this->currentTheme->isHyva()) {
            $this->addHandlesByTheme('hyva');
        }

        if ($this->currentTheme->isLuma()) {
            $this->addHandlesByTheme('luma');
        }

        if ($this->currentTheme->isBreeze()) {
            $this->addHandlesByTheme('breeze');
        }
    }

    private function addHandlesByTheme(string $theme): void
    {
        foreach ($this->layout->getUpdate()->getHandles() as $handle) {
            if (str_starts_with($handle, $theme . '_')) {
                continue;
            }

            $this->layout->getUpdate()->addHandle($theme . '_' . $handle);
        }
    }
}
