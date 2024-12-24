<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;

use Yireo\LokiCheckout\Component\Base\Field\FieldInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiComponents\Component\ComponentInterface;

class AddDebuggingInfo implements ObserverInterface
{
    const DEBUG_BLOCK = 'loki-checkout.utils.debug';

    public function __construct(
        private readonly Config $config,
        private array $blockPrefixes = [],
    ) {
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === $block instanceof Template) {
            return;
        }

        $prefixMatch = false;
        foreach ($this->blockPrefixes as $prefix) {
            if (preg_match('/^'.$prefix.'/', (string)$block->getNameInLayout())) {
                $prefixMatch = true;
            }
        }

        if (false === $prefixMatch) {
            return;
        }

        // Do not debug the debug block itself
        if ($block->getNameInLayout() === self::DEBUG_BLOCK) {
            return;
        }

        $transport = $observer->getEvent()->getTransport();
        $html = trim((string)$transport->getHtml());
        if (empty($html)) {
            return;
        }

        $debugBlock = $block->getLayout()->getBlock(self::DEBUG_BLOCK);
        if (false === $debugBlock instanceof Template) {
            return;
        }

        if (true === $this->config->addDebugBlock()) {
            $html .= $debugBlock->setData([
                'ancestor_block' => $block,
                'component' => $block->getComponent(),
            ])->toHtml();
        }

        $html = trim($html);
        if (true === $this->config->addHtmlHints()) {
            $debugHtml = '<!-- LOKI_DEBUG ';
            $debugLines = $this->getDebugLines($block);
            foreach($debugLines as $debugLineName => $debugLineValue) {
                $debugHtml .= $debugLineName.' = '.$debugLineValue.', ';
            }
            $debugHtml .= ' -->';

            $html = $html . $debugHtml;
        }

        $transport->setHtml($html);

    }

    private function getDebugLines(Template $block): array
    {
        $debugLines = [];
        $debugLines['name'] = $block->getNameInLayout();
        $debugLines['template'] = $block->getTemplate();

        $component = $this->getComponentFromBlock($block);
        if ($component instanceof FieldInterface) {
            $validators = $component->getValidators();
            if (!empty($validators)) {
                $debugLines['validators'] = implode('|', $validators);
            }

            $filters =  $component->getFilters();
            if (!empty($filters)) {
                $debugLines['filters'] = implode('|', $filters);
            }
        }

        return $debugLines;
    }

    /**
     * @param Template $block
     * @return ComponentInterface|null
     */
    private function getComponentFromBlock(Template $block): ?ComponentInterface
    {
        if ($block->getComponent() instanceof ComponentInterface) {
            return $block->getComponent();
        }

        return null;
    }
}
