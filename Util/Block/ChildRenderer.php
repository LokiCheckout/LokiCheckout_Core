<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use InvalidArgumentException;
use Magento\Framework\View\Element\AbstractBlock;
use RuntimeException;

class ChildRenderer extends AbstractRenderer
{
    public function get(string $blockAlias, AbstractBlock $parentBlock, array $data = []): AbstractBlock
    {
        $block = $parentBlock->getChildBlock($blockAlias);
        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('No block alias "%1"', $blockAlias));
        }

        $this->populateBlock($block, $parentBlock, $data);
        $this->setNameInLayout($block, $parentBlock, (string)$block->getAlias());

        return $block;
    }

    public function html(string $blockAlias, AbstractBlock $parentBlock, array $data = [])
    {
        try {
            return (string)$this->get($blockAlias, $parentBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- '.$e->getMessage().' -->'; // @todo: Only report this in debug mode
        }
    }
}
