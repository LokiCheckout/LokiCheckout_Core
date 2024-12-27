<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModelInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Exception\NoComponentFoundException;
use Yireo\LokiComponents\Factory\ViewModelFactory;
use Yireo\LokiCheckout\Util\Block\CssClassFactory;
use Yireo\LokiCheckout\Util\Block\BlockRenderer;
use Yireo\LokiComponents\Util\CamelCaseConvertor;

class AssignAdditionalBlockVariables implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ViewModelFactory $viewModelFactory,
        private readonly CssClassFactory $cssClassFactory,
        private readonly BlockRenderer $blockRenderer,
        private readonly CamelCaseConvertor $camelCaseConvertor,
        private readonly ComponentRegistry $componentRegistry
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
        $block->assign('css', $this->cssClassFactory->create()->setBlock($block));
        $block->assign('blockRenderer', $this->blockRenderer);

        if (!$block->getComponentName()) {
            $componentName = $this->camelCaseConvertor->toCamelCase($block->getJsId());
            $block->setComponentName($componentName);
        }

        $this->allowRendering($block);
    }

    private function allowRendering(AbstractBlock $block): void
    {
        try {
            $component = $this->componentRegistry->getComponentFromBlock($block);
        } catch(NoComponentFoundException $exeption) {
            return;
        }

        $viewModel = $component->getViewModel();
        if ($viewModel instanceof GenericViewModelInterface && false === $viewModel->isAllowRendering()) {
            $block->setTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }
    }
}
