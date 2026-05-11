<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Util\CurrentTheme;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\LayoutInterface;

class AddLayoutHandles
{
    public function __construct(
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private readonly CurrentTheme $currentTheme,
        private readonly CheckoutState $checkoutState,
        private readonly ManagerInterface $eventManager,
    ) {
    }

    public function execute(): void
    {
        $this->addGenericHandles();
        $this->addThemeHandles();
        $this->addAddressGridHandles();
        $this->addHandlesViaObserver();
    }

    private function addGenericHandles(): void
    {
        $theme = $this->config->getTheme();
        $this->addHandle('loki_checkout');
        $this->addHandle('loki_checkout_theme_'.$theme);
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
            if (str_starts_with($handle, $theme.'_')) {
                continue;
            }

            $this->addHandle($theme.'_'.$handle);
        }
    }

    private function addAddressGridHandles(): void
    {
        $gridLayout = $this->config->getGridLayoutName();
        $this->addHandle('loki_checkout_address_'.$gridLayout);

        $countryId = strtolower((string)$this->checkoutState->getQuote()->getShippingAddress()->getCountryId());
        if (!empty($countryId)) {
            $this->addHandle('loki_checkout_address_'.$gridLayout.'_country_'.$countryId);
        }
    }

    private function addHandlesViaObserver(): void
    {
        $this->eventManager->dispatch('loki_checkout_add_layout_handles');
    }

    private function addHandle(string $handle): void
    {
        $this->layout->getUpdate()->addHandle($handle);
    }
}
