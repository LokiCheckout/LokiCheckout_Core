<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Yireo\LokiComponents\Util\CamelCaseConvertor;

/**
 * @deprecated Remove logic based on getComponentName()
 */
class AssignAdditionalBlockVariables implements ObserverInterface
{
    public function __construct(
        private readonly CamelCaseConvertor $camelCaseConvertor,
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $this->isBlockAllowed($block)) {
            return;
        }

        if (!$block->getComponentName()) {
            $componentName = $this->camelCaseConvertor->toCamelCase($block->getJsId());
            $block->setComponentName($componentName);
        }
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
}
