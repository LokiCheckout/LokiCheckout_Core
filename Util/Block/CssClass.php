<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\Template;

class CssClass
{
    private ?Template $block = null;

    public function setBlock(Template $block): BlockCssClass
    {
        $this->block = $block;
        return $this;
    }

    public function __invoke(string $defaultCss = '', string $node = 'block'): string
    {
        $cssClasses = $this->getDefaultCssClasses();
        if ($defaultCss) {
            $cssClasses[$node]['inline'] = $defaultCss;
        }

        // @todo: Document how to overload this in XML layout
        $cssClasses = array_merge($cssClasses, (array)$this->block->getData('css_classes'));
        if (!isset($cssClasses[$node])) {
            $cssClasses[$node] = [];
        }

        if ($node === 'block') {
            $nameInLayout = $this->block->getNameInLayout();
            $blockClass = preg_replace('/([^0-9a-zA-Z]+)/', '-', (string)$nameInLayout);
            $cssClasses[$node]['blockClass'] = $blockClass;
        }

        $css = '';
        foreach ($cssClasses[$node] as $cssClassValue) {
            $css .= ' ' . $cssClassValue;
        }

        return trim($css);
    }

    private function getDefaultCssClasses(): array
    {
        $templateId = preg_replace('/^(.*)::/', '', $this->block->getTemplate());
        $templateId = preg_replace('/\.phtml/', '', $templateId);
        $templateId = str_replace('/', '.', $templateId);

        $defaultBlockName = 'loki.checkout.defaults.'.$templateId;
        $defaultBlock = $this->block->getLayout()->getBlock($defaultBlockName);
        if (empty($defaultBlock)) {
            return [];
        }

        return (array)$defaultBlock->getData('css_classes');
    }
}
