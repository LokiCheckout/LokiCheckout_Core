<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericViewModelInterface;
use Yireo\LokiComponents\Component\Component;
use Yireo\LokiComponents\Component\ComponentHydrator;
use Yireo\LokiComponents\Component\ComponentInterface;

class AdditionalHydration
{
    /**
     * @param ComponentHydrator $subject
     * @param AbstractBlock $block
     * @param ComponentInterface $component
     * @return void
     */
    public function afterHydrate(ComponentHydrator $subject, $return, Component $component): void
    {
        $viewModel = $component->getViewModel();
        if (false === $viewModel instanceof ViewModelInterface) {
            return;
        }

        $block = $viewModel->getBlock();
        $blockName = (string)$block->getNameInLayout();
        if (false === preg_match('/^loki-checkout/', $blockName) > 0) {
            return;
        }

        if ($viewModel instanceof GenericViewModelInterface) {
            $viewModel->initialize();
        }

        if ($viewModel instanceof GenericViewModelInterface && false === $viewModel->isAllowRendering()) {
            $block->setTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }
    }
}
