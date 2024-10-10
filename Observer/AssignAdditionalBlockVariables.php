<?php
declare(strict_types=1);

namespace Yireo\FriggaCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Yireo\FriggaCheckout\Config\Config;
use Yireo\FriggaCheckout\Factory\ViewModelFactory;

class AssignAdditionalBlockVariables implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ViewModelFactory $viewModelFactory
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (false === preg_match('/^frigga./', (string)$block->getNameInLayout())) {
            return;
        }

        $blockClass = str_replace('.', '-', (string)$block->getNameInLayout());
        $block->assign('viewModelFactory', $this->viewModelFactory);
        $block->assign('blockClass', $blockClass);
        $block->assign('debug', $this->config->isDebug());
    }
}
