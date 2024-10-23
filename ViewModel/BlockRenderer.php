<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\AbstractBlock;
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

    /**
     * @param string $blockName
     * @param array $data
     * @return Template
     * @throws RuntimeException
     */
    public function get(string $blockName, array $data = []): AbstractBlock
    {
        $block = $this->layout->getBlock($blockName);
        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('Unexpected block type "%s"', $blockName));
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
        try {
            return (string)$this->get($blockName, $data)?->toHtml();
        } catch(RuntimeException $e) {
            return '';
        }
    }


    public function create(string $templateName, Template $ancestorBlock, ?Component $component = null): Template
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

        return $block;
    }

    public function render(string $templateName, Template $ancestorBlock, ?Component $component = null): string
    {
        return $this->create($templateName, $ancestorBlock, $component)->toHtml();
    }
}
