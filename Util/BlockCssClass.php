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

        $nameInLayout = $this->block->getNameInLayout();
        $blockClass = preg_replace('/([^0-9a-zA-Z]+)/', '-', (string)$nameInLayout);
        $css = $blockClass . ' ' . $css;

        return trim($css);
    }
}
