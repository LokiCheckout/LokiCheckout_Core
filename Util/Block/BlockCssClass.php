<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;

class BlockCssClass
{
    private ?AbstractBlock $block = null;

    public function setBlock(AbstractBlock $block): BlockCssClass
    {
        $this->block = $block;
        return $this;
    }

    public function __invoke(string $css = ''): string
    {
        $overrideCss = (string) $this->block->getData('override_css');
        if (!empty($overrideCss)) {
            $css = $overrideCss;
        }

        $additionalCss = (string) $this->block->getData('additional_css');
        if (!empty($additionalCss)) {
            $css = $css . ' ' . $additionalCss;
        }

        $nameInLayout = $this->block->getNameInLayout();
        $blockClass = preg_replace('/([^0-9a-zA-Z]+)/', '-', (string)$nameInLayout);
        $css = $blockClass . ' ' . $css;

        return trim($css);
    }
}
