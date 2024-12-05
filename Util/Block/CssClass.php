<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\XmlConfig\BlockNotFoundException;
use Yireo\LokiCheckout\Util\Block\CssClassParser\CssClassParserInterface;

class CssClass
{
    private ?Template $block = null;

    /**
     * @param CssClassParserInterface[] $cssClassParsers
     */
    public function __construct(
        private XmlConfig $xmlConfig,
        private array $cssClassParsers = [],
    ) {
    }

    public function setBlock(Template $block): CssClass
    {
        $this->block = $block;

        return $this;
    }

    public function __invoke(string $defaultCss = '', string $scope = 'block'): string
    {
        $cssClasses = $this->getDefaultCssClasses();
        if (!isset($cssClasses[$scope])) {
            $cssClasses[$scope] = [];
        }

        $defaultCss = trim($defaultCss);
        if (!empty($defaultCss)) {
            $cssClasses[$scope]['inline'] = $defaultCss;
        }

        $blockCssClasses = (array)$this->block->getData('css_classes');
        $cssClasses = array_merge_recursive($cssClasses, $blockCssClasses);

        // @todo: Carefully review this. Are we insane?
        if ($scope === 'block') {
            $gridClassesFromXmlConfig = $this->getGridClassesFromXmlConfig();
            if (!empty($gridClassesFromXmlConfig)) {
                $cssClasses = array_merge_recursive($cssClasses, [
                    'block' => ['grid' => $gridClassesFromXmlConfig],
                ]);
            }
        }

        // @todo: Beautify this
        $nameInLayout = $this->block->getNameInLayout();
        $defaultsBlock = $this->block->getLayout()->getBlock('loki-checkout.css_classes');
        $blockData = $defaultsBlock->getData($nameInLayout);
        if (!empty($blockData)) {
            $cssClasses = array_merge_recursive($cssClasses, $blockData);
        }

        $cssClasses[$scope] = $this->parse($cssClasses[$scope]);

        $css = '';
        foreach ($cssClasses[$scope] as $cssClassValue) {
            if (!is_string($cssClassValue)) {
                $cssClassValue = array_pop($cssClassValue);
            }

            $css .= ' '.$cssClassValue;
        }

        $cssName = $this->block->getCssName();
        if (empty($cssName)) {
            $cssName = preg_replace('/([^0-9a-zA-Z]+)/', '-', (string)$nameInLayout);
        }

        if ($scope === 'block') {
            $scopeClass = $cssName;
        } else {
            $scopeClass = $cssName .'__'.$scope;
        }

        $css = $scopeClass.' '.trim($css);

        return trim($css);
    }

    private function getGridClassesFromXmlConfig(): string
    {
        $parentBlock = $this->block->getParentBlock();
        if (empty($parentBlock)) {
            return '';
        }

        $gridLayoutName = (string)$parentBlock->getGridLayout();
        if (empty($gridLayoutName)) {
            return '';
        }

        $blockAlias = $this->block->getLayout()->getElementAlias($this->block->getNameInLayout());
        if (empty($blockAlias)) {
            return '';
        }

        $gridLayout = $this->xmlConfig->getGridLayout($gridLayoutName);

        try {
            $block = $gridLayout->getBlock($blockAlias);
        } catch(BlockNotFoundException $exception) {
            return '';
        }

        return $block->getGridClass();
    }

    private function getDefaultCssClasses(): array
    {
        $templateId = preg_replace('/^(.*)::/', '', (string)$this->block->getTemplate());
        $templateId = preg_replace('/\.phtml/', '', $templateId);
        $templateId = str_replace('/', '.', $templateId);

        $defaultBlockName = 'loki-checkout.defaults.'.$templateId;
        $defaultBlock = $this->block->getLayout()->getBlock($defaultBlockName);
        if (empty($defaultBlock)) {
            return [];
        }

        return (array)$defaultBlock->getData('css_classes');
    }

    private function parse(array $cssClasses): array
    {
        foreach ($this->cssClassParsers as $cssClassParser) {
            $cssClasses = $cssClassParser->parse($cssClasses);
        }

        return $cssClasses;
    }
}
