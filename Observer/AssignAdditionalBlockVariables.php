<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiComponents\Factory\ViewModelFactory;
use Yireo\LokiCheckout\Util\Block\CssClass;
use Yireo\LokiCheckout\Util\Block\BlockRenderer;
use Yireo\LokiComponents\Util\CamelCaseConvertor;

class AssignAdditionalBlockVariables implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ViewModelFactory $viewModelFactory,
        private readonly CssClass $cssClass,
        private readonly BlockRenderer $blockRenderer,
        private readonly CamelCaseConvertor $camelCaseConvertor
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        if (false === preg_match('/^loki-checkout/', (string)$block->getNameInLayout())) {
            return;
        }

        $blockClass = str_replace('.', '-', (string)$block->getNameInLayout());
        $block->assign('blockClass', $blockClass); // @todo: deprecate this

        $block->assign('viewModelFactory', $this->viewModelFactory);
        $block->assign('debug', $this->config->isDebug()); // @todo: Is this relevant
        $block->assign('css', $this->cssClass->setBlock($block));
        $block->assign('blockRenderer', $this->blockRenderer);

        if (!$block->getComponentName()) {
            $componentName = $this->camelCaseConvertor->toCamelCase($block->getJsId());
            $block->setComponentName($componentName);
        }
    }
}
