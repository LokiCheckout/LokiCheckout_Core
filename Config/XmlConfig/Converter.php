<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig;

use DOMDocument;
use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    /**
     * @param DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        return [
            'gridLayouts' => $this->getGridLayouts($source)
        ];
    }

    private function getGridLayouts(DOMDocument $source): array
    {
        $gridLayouts = [];
        $gridLayoutElements = $source->getElementsByTagName('grid_layout');

        foreach ($gridLayoutElements as $gridLayoutElement) {
            $blocks = [];
            foreach ($gridLayoutElement->getElementsByTagName('block') as $block) {
                $blocks[] = [
                    'alias' => (string)$block->getAttribute('alias'),
                    'sortOrder' => (int)$block->getAttribute('sortOrder'),
                    'gridClass' => (string)$block->getAttribute('gridClass'),
                ];
            }

            $gridName = (string)$gridLayoutElement->getAttribute('name');
            $defaultSortOrder = (int)$gridLayoutElement->getAttribute('defaultSortOrder');
            $defaultGridClass = (string)$gridLayoutElement->getAttribute('defaultGridClass');

            $gridLayouts[$gridName] = [
                'defaultSortOrder' => !empty($defaultSortOrder) ? $defaultSortOrder : 99,
                'defaultGridClass' => !empty($defaultGridClass) ? $defaultGridClass : 'col-span-6',
                'blocks' => $blocks,
            ];
        }

        return $gridLayouts;
    }
}
