<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magewirephp\Magewire\Component;
use RuntimeException;

class BlockRenderer implements ArgumentInterface
{
    public function __construct(
        private LayoutInterface $layout
    ) {
    }

    public function get(string $blockName, array $data = []): Template
    {
        $block = $this->layout->getBlock($blockName);
        if (false === $block instanceof Template) {
            throw new RuntimeException('Unexpected block type');
        }

        $block->addData($data);
        return $block;
    }

    /**
     * @param string $blockName
     * @param array $data
     * @return string
     */
    public function html(string $blockName, array $data = []): string
    {
        return $this->get($blockName, $data)->toHtml();
    }

    public function render(string $templateName, Template $ancestorBlock, ?Component $component = null): string
    {
        if (false === strstr($templateName, '::')) {
            $templateName = 'Yireo_LokiCheckout::' . $templateName;
        }

        $block = $this->layout->createBlock(Template::class)
            ->setAncestorBlock($ancestorBlock)
            ->setTemplate($templateName);

        if ($component instanceof Component) {
            $block->setData('component', $component);
        }

        return $block->toHtml();
    }
}
