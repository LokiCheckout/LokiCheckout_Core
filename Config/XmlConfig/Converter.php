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
            $defaultSortOrder = (int)$gridLayoutElement->getAttribute('defaultSortOrder');
            $defaultColSpan = (int)$gridLayoutElement->getAttribute('defaultColSpan');
            if (empty($defaultColSpan)) {
                $defaultColSpan = 6;
            }

            $defaultMdColSpan = (int)$gridLayoutElement->getAttribute('defaultMdColSpan');

            foreach ($gridLayoutElement->getElementsByTagName('block') as $blockElement) {
                $sortOrder = (int)$blockElement->getAttribute('sortOrder');
                $colSpan = (int)$blockElement->getAttribute('colSpan');

                $blocks[] = [
                    'alias' => (string)$blockElement->getAttribute('alias'),
                    'sortOrder' => !empty($sortOrder) ? $sortOrder : 99,
                    'colSpan' => !empty($colSpan) ? $colSpan : $defaultColSpan,
                    'mdColSpan' => (int)$blockElement->getAttribute('mdColSpan') ?? $defaultMdColSpan,
                ];
            }

            $gridName = (string)$gridLayoutElement->getAttribute('name');
            $label = (string)$gridLayoutElement->getAttribute('label');
            $countryId = (string)$gridLayoutElement->getAttribute('countryId');


            $gridLayouts[$gridName] = [
                'label' => $label,
                'countryId' => $countryId,
                'defaultSortOrder' => !empty($defaultSortOrder) ? $defaultSortOrder : 99,
                'defaultColSpan' => $defaultColSpan,
                'defaultMdColSpan' => !empty($defaultMdColSpan) ? $defaultMdColSpan : 6,
                'blocks' => $blocks,
            ];
        }

        return $gridLayouts;
    }
}
