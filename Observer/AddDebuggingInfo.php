<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Yireo\FriggaCheckout\Config\Config;
use Yireo\FriggaCheckout\Factory\ViewModelFactory;

class AddDebuggingInfo implements ObserverInterface
{
    const DEBUG_BLOCK = 'frigga.checkout.utils.debug';

    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (false === preg_match('/^frigga./', (string)$block->getNameInLayout()) > 0) {
            return;
        }

        if ($block->getNameInLayout() === self::DEBUG_BLOCK) {
            return;
        }

        if (false === $this->config->isDebug()) {
            return;
        }

        $transport = $observer->getEvent()->getTransport();
        $html = $transport->getHtml();
        if (empty($html)) {
            return;
        }

        $debugBlock = $block->getLayout()->getBlock(self::DEBUG_BLOCK);
        if (false === $debugBlock instanceof Template) {
            return;
        }

        $html .= $debugBlock->setData([
            'ancestor_block' => $block,
            'component' => $block->getMagewire()
        ])->toHtml();

        $transport->setHtml($html);

    }
}
