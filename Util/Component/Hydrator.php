<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Util\SetterInjection;
use Yireo\LokiComponents\Component\ComponentInterface;

class Hydrator
{
    public function __construct(
        private SetterInjection $setterInjection
    ) {
    }

    public function hydrate(AbstractBlock $block, ComponentInterface $component)
    {
        $this->setterInjection->injectDependencies($component);
        $block->setComponent($component);
        $block->assign('component', $component);

        if ($component instanceof Generic) {
            $component->setBlock($block);
            $component->initialize();
        }

        if ($component instanceof Generic && false === $component->isAllowRendering()) {
            $block->setTemplate('Yireo_LokiCheckout::utils/not-rendered.phtml');
        }
    }
}
