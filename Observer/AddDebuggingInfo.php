<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;

use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Exception\NoComponentFoundException;

class AddDebuggingInfo implements ObserverInterface
{
    const DEBUG_BLOCK = 'loki-checkout.utils.debug';

    public function __construct(
        private readonly ComponentRegistry $componentRegistry,
        private readonly Config $config,
        private readonly array $blockPrefixes = [],
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
            if (preg_match('/^' . $prefix . '/', (string)$block->getNameInLayout())) {
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
        if ($html === '' || $html === '0') {
            return;
        }

        $debugBlock = $block->getLayout()->getBlock(self::DEBUG_BLOCK);
        if (false === $debugBlock instanceof Template) {
            return;
        }

        if ($this->config->addDebugBlock()) {
            $html .= $debugBlock->setData([
                'ancestor_block' => $block,
                'component' => $block->getComponent(),
            ])->toHtml();
        }

        $html = trim($html);
        if ($this->config->addHtmlHints()) {
            $debugHtml = '<!-- LOKI_DEBUG ';
            $debugLines = $this->getDebugLines($block);
            foreach ($debugLines as $debugLineName => $debugLineValue) {
                $debugHtml .= $debugLineName . ' = ' . $debugLineValue . ', ';
            }
            $debugHtml .= ' -->';

            $html .= $debugHtml;
        }

        $transport->setHtml($html);

    }

    private function getDebugLines(Template $block): array
    {
        $debugLines = [];
        $debugLines['name'] = $block->getNameInLayout();
        $debugLines['template'] = $block->getTemplate();

        try {
            $component = $this->componentRegistry->getComponentFromBlock($block);
        } catch (NoComponentFoundException $exception) {
            return $debugLines;
        }

        $validators = $component->getValidators();
        if ($validators !== []) {
            $debugLines['validators'] = implode('|', $validators);
        }

        $filters = $component->getFilters();
        if ($filters !== []) {
            $debugLines['filters'] = implode('|', $filters);
        }

        return $debugLines;
    }
}
