<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\View\Element\AbstractBlock;

class BlockCssClass
{
    private ?AbstractBlock $block = null;

    public function setBlock(AbstractBlock $block): BlockCssClass
    {
        $this->block = $block;
        return $this;
    }

    public function __invoke(string $default = ''): string
    {
        $css = (string) $this->block->getData('css');
        if (empty($css)) {
            $css = $default;
        }

        $blockClass = str_replace('.', '-', (string)$this->block->getNameInLayout());
        $css = $blockClass . ' ' . $css;

        return trim($css);
    }
}
