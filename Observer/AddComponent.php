<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use RuntimeException;
use Yireo\LokiCheckout\Util\Component\Hydrator;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ComponentRegistry;

class AddComponent implements ObserverInterface
{
    public function __construct(
        private ComponentRegistry $componentRegistry,
        private Hydrator $hydrator
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        $blockName = (string)$block->getNameInLayout();
        if (false === preg_match('/^loki-checkout/', (string)$blockName) > 0) {
            return;
        }

        try {
            $component = $this->componentRegistry->getComponentFromBlockName($blockName);
        } catch (RuntimeException $exception) {
            return;
        }

        if (false === $component instanceof ComponentInterface) {
            return;
        }

        $this->hydrator->hydrate($block, $component);
    }
}
