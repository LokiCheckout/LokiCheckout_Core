<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Block;


use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Template;
use RuntimeException;
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
        foreach ($this->getComponents() as $componentName => $templateName) {
            $block = $this->getScriptBlockFromComponent($componentName, $templateName);
            if (false === $block instanceof BlockInterface) {
                continue;
            }

            $html .= $block->toHtml();
        }

        return $html;
    }

    private function getComponents(): array
    {
        return (array)$this->getData('components');
    }

    private function getScriptBlockFromComponent(string $componentName, string $templateName): ?Template
    {
        try {
            $component = $this->componentRegistry->getComponentById($componentName);
        } catch(RuntimeException $e) {
            return null;
        }

        $scriptName = $componentName . '.' . $componentName;
        $block = $this->getLayout()->createBlock(Template::class, $scriptName);
        $block->setTemplate($templateName);
        $block->setComponent($component);
        return $block;
    }
}
