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
            $grids = [];
            $defaultSortOrder = (int)$gridLayoutElement->getAttribute('defaultSortOrder');
            $defaultColSpan = (int)$gridLayoutElement->getAttribute('defaultColSpan');
            if ($defaultColSpan === 0) {
                $defaultColSpan = 12;
            }

            $defaultMdColSpan = (int)$gridLayoutElement->getAttribute('defaultMdColSpan');
            if ($defaultMdColSpan === 0) {
                $defaultMdColSpan = 6;
            }

            foreach ($gridLayoutElement->getElementsByTagName('grid') as $gridElement) {
                $blocks = [];
                foreach ($gridElement->getElementsByTagName('block') as $blockElement) {
                    $sortOrder = $defaultSortOrder;
                    if ($blockElement->hasAttribute('sortOrder')) {
                        $sortOrder = (int)$blockElement->getAttribute('sortOrder');
                    }

                    $colSpan = $defaultColSpan;
                    if ($blockElement->hasAttribute('colSpan')) {
                        $colSpan = (int)$blockElement->getAttribute('colSpan');
                    }

                    $mdColSpan = $defaultMdColSpan;
                    if ($blockElement->hasAttribute('mdColSpan')) {
                        $mdColSpan = (int)$blockElement->getAttribute('mdColSpan');
                    }

                    $blocks[] = [
                        'alias' => (string)$blockElement->getAttribute('alias'),
                        'sortOrder' => $sortOrder,
                        'colSpan' => $colSpan,
                        'mdColSpan' => $mdColSpan,
                    ];
                }

                $grids[] = [
                    'name' => (string)$gridElement->getAttribute('name'),
                    'countryId' => (string)$gridElement->getAttribute('countryId'),
                    'blocks' => $blocks,
                ];
            }

            $gridName = (string)$gridLayoutElement->getAttribute('name');
            $label = (string)$gridLayoutElement->getAttribute('label');
            $countryId = (string)$gridLayoutElement->getAttribute('countryId');

            $gridLayouts[$gridName] = [
                'label' => $label,
                'countryId' => $countryId,
                'defaultSortOrder' => $defaultSortOrder === 0 ? 99 : $defaultSortOrder,
                'defaultColSpan' => $defaultColSpan,
                'defaultMdColSpan' => $defaultMdColSpan === 0 ? 6 : $defaultMdColSpan,
                'grids' => $grids,
            ];
        }

        return $gridLayouts;
    }
}
