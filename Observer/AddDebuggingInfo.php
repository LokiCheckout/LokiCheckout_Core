<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Factory\ViewModelFactory;

class AddDebuggingInfo implements ObserverInterface
{
    const DEBUG_BLOCK = 'loki-checkout.utils.debug';

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

        // @todo: Configure which blocks to allow for this
        if (false === preg_match('/^loki-checkout/', (string)$block->getNameInLayout()) > 0) {
            return;
        }

        // Do not debug the debug block itself
        if ($block->getNameInLayout() === self::DEBUG_BLOCK) {
            return;
        }



        $transport = $observer->getEvent()->getTransport();
        $html = trim((string)$transport->getHtml());
        if (empty($html)) {
            return;
        }

        $debugBlock = $block->getLayout()->getBlock(self::DEBUG_BLOCK);
        if (false === $debugBlock instanceof Template) {
            return;
        }

        if (true === $this->config->addDebugBlock()) {
            $html .= $debugBlock->setData([
                'ancestor_block' => $block,
                'component' => $block->getMagewire()
            ])->toHtml();
        }

        if (true === $this->config->addHtmlHints()) {
            $html = '<!-- LOKI_DEBUG '
                . 'block ' . $block->getNameInLayout() . ', '
                . 'template ' . $block->getTemplate() . ' '
                .' -->' . $html;
        }

        $transport->setHtml($html);

    }
}
