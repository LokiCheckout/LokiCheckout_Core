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

class EnableCheckout implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private CheckoutSession $checkoutSession,
        private CustomerSession $customerSession,
        private UrlFactory $urlFactory,
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

        $this->layout->getUpdate()->addHandle('loki_checkout');
        $this->layout->getUpdate()->addHandle('loki_checkout_theme_'.$this->config->getTheme());

        if (in_array('hyva_checkout_index_index', $handles)) {
            $this->layout->getUpdate()->addHandle('hyva_loki_checkout');
        }

        $this->checkoutSession->setLokiRedirectUrl('');
        $this->customerSession->setBeforeAuthUrl($this->urlFactory->create()->getUrl('checkout/index/index'));
    }
}
