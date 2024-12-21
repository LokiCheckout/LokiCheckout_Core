<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Util\SetterInjection;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Mutator\Hydrator;
use Yireo\LokiComponents\Mutator\MutatorInterface;

class AdditionalMutatorHydration
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
    public function beforeHydrate(
        Hydrator $subject,
        MutatorInterface $mutator,
        AbstractBlock $block,
        ComponentInterface $component
    ): array {
        $blockName = (string)$block->getNameInLayout();
        if (false === preg_match('/^loki-checkout/', (string)$blockName) > 0) {
            return [$mutator, $block, $component];
        }

        if (method_exists($mutator, 'setComponent')) {
            $mutator->setComponent($component);
        }

        if (method_exists($mutator, 'setBlock')) {
            $mutator->setBlock($block);
        }

        $this->setterInjection->injectIntoMutator($mutator);

        return [$mutator, $block, $component];
    }
}
