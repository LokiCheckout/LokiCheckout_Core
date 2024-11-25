<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Block;


use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Magewire\ComponentRegistry;

class ComponentInitializer extends AbstractBlock
{
    public function __construct(
        private ComponentRegistry $componentRegistry,
        Context $context,
        array $data = []) {
        parent::__construct($context, $data);
    }

    protected function _toHtml()
    {
        $html = '';
        echo 'JISSE222';
        foreach ($this->getComponents() as $componentName => $templateName) {
            $html .= $this->getScriptBlockFromComponent($componentName, $templateName)->toHtml();
        }

        return $html;
    }

    private function getComponents(): array
    {
        return (array)$this->getData('components');
    }

    private function getScriptBlockFromComponent(string $componentName, string $templateName): Template
    {
        $component = $this->componentRegistry->getComponentById($componentName);
        $scriptName = $componentName . '.' . $componentName;
        echo $component->getParent()->getNameInLayout();
        $block = $this->getLayout()->createBlock(Template::class, $scriptName);
        $block->setTemplate($templateName);
        $block->setComponent($component);
        return $block;
    }
}
