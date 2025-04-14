<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\LayoutInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Util\CurrentTheme;

class EnableCheckout implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private CheckoutSession $checkoutSession,
        private CustomerSession $customerSession,
        private UrlFactory $urlFactory,
        private CurrentTheme $currentTheme
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
        $this->layout->getUpdate()->addHandle('loki_checkout_theme_'.$theme);
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
            if (str_starts_with($handle, $theme.'_')) {
                continue;
            }

            $this->layout->getUpdate()->addHandle($theme.'_'.$handle);
        }
    }
}
