<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Factory\ViewModelFactory;
use Yireo\LokiCheckout\Util\BlockAttribute;
use Yireo\LokiCheckout\Util\BlockCssClass;

class AssignAdditionalBlockVariables implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ViewModelFactory $viewModelFactory,
        private readonly BlockCssClass $blockCssClass,
        private readonly BlockAttribute $blockAttribute
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (false === preg_match('/^loki./', (string)$block->getNameInLayout())) {
            return;
        }

        $blockClass = str_replace('.', '-', (string)$block->getNameInLayout());
        $block->assign('viewModelFactory', $this->viewModelFactory);
        $block->assign('blockClass', $blockClass);
        $block->assign('debug', $this->config->isDebug());
        $block->assign('blockCss', $this->blockCssClass->setBlock($block));
        $block->assign('blockAttribute', $this->blockAttribute->setBlock($block));
    }
}
