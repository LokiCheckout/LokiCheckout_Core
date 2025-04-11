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
            'themes' => $this->getThemes($source),
            'gridLayouts' => $this->getGridLayouts($source)
        ];
    }

    private function getThemes(DOMDocument $source): array
    {
        $themes = [];
        $themeElements = $source->getElementsByTagName('theme');

        foreach ($themeElements as $themeElement) {
            $steps = [];
            foreach ($themeElement->getElementsByTagName('step') as $stepElement) {
                $steps[] = [
                    'name' => (string)$stepElement->getAttribute('name'),
                    'blockName' => (string)$stepElement->getAttribute('blockName'),
                ];
            }

            $themeName = (string)$themeElement->getAttribute('name');
            $themeLabel = (string)$themeElement->getAttribute('label');

            $themes[$themeName] = [
                'name' => $themeName,
                'label' => $themeLabel,
                'steps' => $steps,
            ];
        }

        return $themes;
    }

    private function getGridLayouts(DOMDocument $source): array
    {
        $gridLayouts = [];
        $gridLayoutElements = $source->getElementsByTagName('grid_layout');

        foreach ($gridLayoutElements as $gridLayoutElement) {
            $blocks = [];
            foreach ($gridLayoutElement->getElementsByTagName('block') as $blockElement) {
                $blocks[] = [
                    'alias' => (string)$blockElement->getAttribute('alias'),
                    'sortOrder' => (int)$blockElement->getAttribute('sortOrder'),
                    'gridClass' => (string)$blockElement->getAttribute('gridClass'),
                ];
            }

            $gridName = (string)$gridLayoutElement->getAttribute('name');
            $label = (string)$gridLayoutElement->getAttribute('label');
            $countryId = (string)$gridLayoutElement->getAttribute('countryId');
            $defaultSortOrder = (int)$gridLayoutElement->getAttribute('defaultSortOrder');
            $defaultGridClass = (string)$gridLayoutElement->getAttribute('defaultGridClass');

            $gridLayouts[$gridName] = [
                'label' => $label,
                'countryId' => $countryId,
                'defaultSortOrder' => !empty($defaultSortOrder) ? $defaultSortOrder : 99,
                'defaultGridClass' => !empty($defaultGridClass) ? $defaultGridClass : 'col-span-6',
                'blocks' => $blocks,
            ];
        }

        return $gridLayouts;
    }
}
