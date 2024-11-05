<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;

class BlockAttribute
{
    private ?AbstractBlock $block = null;

    public function setBlock(AbstractBlock $block): BlockAttribute
    {
        $this->block = $block;
        return $this;
    }

    public function __invoke(): string
    {
        $attributes = (array) $this->block->getData('html_attributes');
        if (empty($attributes)) {
            return '';
        }

        $htmlAttribute = '';
        foreach ($attributes as $attributeName => $attributeValue) {
            $htmlAttribute .= ' '.$attributeName.'="'.$attributeValue.'"';
        }

        return trim($htmlAttribute);
    }
}
