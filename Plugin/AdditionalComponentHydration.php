<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericInterface;
use Yireo\LokiCheckout\Component\Form\Field\AddressField\Firstname;
use Yireo\LokiCheckout\Util\SetterInjection;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\Hydrator;

class AdditionalComponentHydration
{
    public function __construct(
        private SetterInjection $setterInjection
    ) {
    }

    /**
     * @param Hydrator $subject
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
     * @param Hydrator $subject
     * @param AbstractBlock $block
     * @param ComponentInterface $component
     * @return void
     */
    public function afterHydrate(Hydrator $subject, $return, AbstractBlock $block, ComponentInterface $component): void
    {
        $blockName = (string)$block->getNameInLayout();
        if (false === preg_match('/^loki-checkout/', $blockName) > 0) {
            return;
        }

        if ($component instanceof Generic) {
            $this->setterInjection->injectIntoComponent($component);
            $component->initialize();
        }

        if ($component instanceof Generic && false === $component->isAllowRendering()) {
            $block->setTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }
    }
}
