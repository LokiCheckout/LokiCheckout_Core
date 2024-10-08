<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;
use Yireo\FriggaCheckout\Config\Config;

class EnableCheckout implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly LayoutInterface $layout,
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

        $this->layout->getUpdate()->addHandle('frigga_checkout');
    }
}
