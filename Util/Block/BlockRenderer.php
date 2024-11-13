<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use InvalidArgumentException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
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

    /**
     * @param string $blockName
     * @param array $data
     * @return Template
     * @throws RuntimeException
     */
    public function get(string $blockIdentifier, ?AbstractBlock $parentBlock = null, array $data = []): AbstractBlock
    {
        if (str_ends_with($blockIdentifier, '.phtml')) {
            $block = $this->getBlockFromTemplate($blockIdentifier);
        } else {
            $block = $this->layout->getBlock($blockIdentifier);
        }

        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('Unexpected block type "%1"', $blockIdentifier));
        }

        $block->setAncestorBlock($parentBlock);

        if ($parentBlock instanceof AbstractBlock) {
            $component = $parentBlock->getData('magewire');
            if ($component instanceof Component) {
                $block->setData('component', $component);
            }

            $component = $parentBlock->getData('component');
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
     * @param array $data
     * @return string
     */
    public function html(string $blockName, ?AbstractBlock $parentBlock = null, array $data = []): string
    {
        try {
            return (string)$this->get($blockName, $parentBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- '.$e->getMessage().' -->'; // @todo: Only report this in debug mode
        }
    }

    /**
     * @param string $templateName
     * @return Template
     */
    private function getBlockFromTemplate(string $templateName): Template
    {
        if (false === strstr($templateName, '::')) {
            $templateName = 'Yireo_LokiCheckout::'.$templateName;
        }

        return $this->layout->createBlock(Template::class)->setTemplate($templateName);
    }
}
