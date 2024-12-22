<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericInterface;
use Yireo\LokiCheckout\Component\Form\Field\AddressField\Firstname;
use Yireo\LokiCheckout\Util\SetterInjection;
use Yireo\LokiComponents\Component\Component;
use Yireo\LokiComponents\Component\ComponentHydrator;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\Hydrator;

class AdditionalComponentHydration
{
    public function __construct(
        private SetterInjection $setterInjection
    ) {
    }

    /**
     * @param ComponentHydrator $subject
     * @param AbstractBlock $block
     * @param ComponentInterface $component
     * @return array
     */
    /*public function beforeHydrate(Hydrator $subject, AbstractBlock $block, ComponentInterface $component): array
    {
        $blockName = (string)$block->getNameInLayout();
        if (false === preg_match('/^loki-checkout/', (string)$blockName) > 0) {
            return [$block, $component];
        }

        if (false === $component instanceof GenericInterface) {
            return [$block, $component];
        }


        return [$block, $component];
    }*/

    /**
     * @param ComponentHydrator $subject
     * @param AbstractBlock $block
     * @param ComponentInterface $component
     * @return void
     */
    public function afterHydrateBlock(ComponentHydrator $subject, $return, AbstractBlock $block, Component $component): void
    {
        $blockName = (string)$block->getNameInLayout();
        if (false === preg_match('/^loki-checkout/', $blockName) > 0) {
            return;
        }

        $viewModel = $component->getViewModel();

        if ($viewModel instanceof Generic) {
            $this->setterInjection->injectIntoViewModel($viewModel);
            $viewModel->initialize();
        }

        if ($viewModel instanceof Generic && false === $viewModel->isAllowRendering()) {
            $block->setTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }
    }
}
