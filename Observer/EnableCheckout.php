<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\LayoutInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Util\CurrentTheme;

class EnableCheckout implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private readonly CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
        private readonly UrlFactory $urlFactory,
        private readonly CurrentTheme $currentTheme
    ) {
    }

    public function execute(Observer $observer)
    {
        if (false === $this->config->isEnabled()) {
            return;
        }

        // @todo: Better check for this?
        $handles = $this->layout->getUpdate()->getHandles();
        if (false === in_array('checkout_index_index', $handles)) {
            return;
        }

        $theme = $this->config->getTheme();

        $this->layout->getUpdate()->addHandle('loki_checkout');
        $this->layout->getUpdate()->addHandle('loki_checkout_theme_' . $theme);
        $this->addThemeHandles();

        $this->checkoutSession->setLokiRedirectUrl('');
        $this->customerSession->setBeforeAuthUrl($this->urlFactory->create()->getUrl('checkout/index/index'));
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
