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

    public function __invoke(string $defaultCss = '', string $scope = 'block'): string
    {
        $cssClasses = $this->getDefaultCssClasses();
        if ($defaultCss) {
            $cssClasses[$scope]['inline'] = $defaultCss;
        }

        // @todo: Document how to overload this in XML layout
        $cssClasses = array_merge($cssClasses, (array)$this->block->getData('css_classes'));
        if (!isset($cssClasses[$scope])) {
            $cssClasses[$scope] = [];
        }

        if ($scope === 'block') {
            $nameInLayout = $this->block->getNameInLayout();
            $blockClass = preg_replace('/([^0-9a-zA-Z]+)/', '-', (string)$nameInLayout);
            $cssClasses[$scope]['blockClass'] = $blockClass;
        }

        $css = '';
        foreach ($cssClasses[$scope] as $cssClassValue) {
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
