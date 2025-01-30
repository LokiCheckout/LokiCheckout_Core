<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModelInterface;
use Yireo\LokiCheckout\Util\Block\ChildRenderer;
use Yireo\LokiCheckout\Util\Block\TemplateRenderer;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Exception\NoComponentFoundException;
use Yireo\LokiCheckout\Util\Block\BlockRenderer;
use Yireo\LokiComponents\Util\CamelCaseConvertor;

class AssignAdditionalBlockVariables implements ObserverInterface
{
    public function __construct(
        private readonly BlockRenderer $blockRenderer,
        private readonly ChildRenderer $childRenderer,
        private readonly TemplateRenderer $templateRenderer,
        private readonly CamelCaseConvertor $camelCaseConvertor,
        private readonly ComponentRegistry $componentRegistry
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $this->isBlockAllowed($block)) {
            return;
        }

        $block->assign('blockRenderer', $this->blockRenderer);
        $block->assign('childRenderer', $this->childRenderer);
        $block->assign('templateRenderer', $this->templateRenderer);

        if (!$block->getComponentName()) {
            $componentName = $this->camelCaseConvertor->toCamelCase($block->getJsId());
            $block->setComponentName($componentName);
        }

        $this->allowRendering($block);
    }

    private function isBlockAllowed(AbstractBlock $block): bool
    {
        if (false === $block instanceof Template) {
            return false;
        }

        if (preg_match('/^loki-checkout/', (string)$block->getNameInLayout())) {
            return true;
        }

        if (preg_match('/^loki-components/', (string)$block->getNameInLayout())) {
            return true;
        }

        return false;
    }

    private function allowRendering(AbstractBlock $block): void
    {
        try {
            $component = $this->componentRegistry->getComponentFromBlock($block);
        } catch (NoComponentFoundException $exception) {
            return;
        }

        $viewModel = $component->getViewModel();
        if ($viewModel instanceof CheckoutViewModelInterface && false === $viewModel->isAllowRendering()) {
            $block->setTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
            $block->setNotRendered(true);
        }
    }
}
