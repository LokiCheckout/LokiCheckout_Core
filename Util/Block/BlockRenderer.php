<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use InvalidArgumentException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magewirephp\Magewire\Component;
use RuntimeException;

class BlockRenderer
{
    public function __construct(
        private LayoutInterface $layout
    ) {
    }

    public function getChild(string $blockAlias, AbstractBlock $parentBlock, array $data = []): AbstractBlock
    {
        $block = $parentBlock->getChildBlock($blockAlias);

        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('Unexpected block type "%1"', $blockAlias));
        }

        $block->setAncestorBlock($parentBlock);
        $block->addData($data);

        return $block;
    }

    public function getChildHtml(string $blockAlias, AbstractBlock $parentBlock, array $data = [])
    {
        try {
            return (string)$this->getChild($blockAlias, $parentBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- ' . $e->getMessage() . ' -->'; // @todo: Only report this in debug mode
        }
    }

    /**
     * @param string $blockName
     * @param array  $data
     *
     * @return Template
     * @throws RuntimeException
     */
    public function get(string $blockIdentifier, ?AbstractBlock $ancestorBlock = null, array $data = []): AbstractBlock
    {
        if (str_ends_with($blockIdentifier, '.phtml')) {
            $block = $this->getBlockFromTemplate($blockIdentifier);
        } else {
            $block = $this->layout->getBlock($blockIdentifier);
        }

        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('Unexpected block type "%1"', $blockIdentifier));
        }

        $block->setAncestorBlock($ancestorBlock);

        if ($ancestorBlock instanceof AbstractBlock && false === $block->getData('magewire') instanceof Component) {
            $component = $ancestorBlock->getData('magewire');
            if ($component instanceof Component) {
                $block->setData('component', $component);
            }

            $component = $ancestorBlock->getData('component');
            if ($component instanceof Component) {
                $block->setData('component', $component);
            }
        }

        if (!empty($data)) {
            $block->addData($data);
        }

        return $block;
    }

    /**
     * @param string $blockName
     * @param array  $data
     *
     * @return string
     */
    public function html(string $blockName, ?AbstractBlock $parentBlock = null, array $data = []): string
    {
        try {
            return (string)$this->get($blockName, $parentBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- ' . $e->getMessage() . ' -->'; // @todo: Only report this in debug mode
        }
    }

    /**
     * @param string $templateName
     *
     * @return Template
     */
    private function getBlockFromTemplate(string $templateName): Template
    {
        if (false === strstr($templateName, '::')) {
            $templateName = 'Yireo_LokiCheckout::' . $templateName;
        }

        return $this->layout->createBlock(Template::class)->setTemplate($templateName);
    }
}
