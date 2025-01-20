<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use InvalidArgumentException;
use Magento\Framework\View\Element\AbstractBlock;
use RuntimeException;

class ChildRenderer extends AbstractRenderer
{
    public function get(string $blockAlias, AbstractBlock $ancestorBlock, array $data = []): AbstractBlock
    {
        $this->ancestorBlock = $ancestorBlock;
        $block = $ancestorBlock->getChildBlock($blockAlias);
        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('No block alias "%1"', $blockAlias));
        }

        $this->populateBlock($block, $data);
        $this->setNameInLayout($block, (string)$block->getAlias());

        return $block;
    }

    public function html(string $blockAlias, AbstractBlock $ancestorBlock, array $data = [])
    {
        try {
            return (string)$this->get($blockAlias, $ancestorBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- '.$e->getMessage().' -->'; // @todo: Only report this in debug mode
        }
    }
}
