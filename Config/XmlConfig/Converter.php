<?php declare(strict_types=1);

namespace LokiCheckout\Core\Config\XmlConfig;

use DOMDocument;
use Magento\Framework\Config\ConverterInterface;
use RuntimeException;

class Converter implements ConverterInterface
{
    public function __construct(
        private Defaults $defaults
    ) {
    }

    /**
     * @param DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        return [
            'themes' => $this->getThemes($source),
            'gridLayouts' => $this->getGridLayouts($source),
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
                    'sortOrder' => (int)$stepElement->getAttribute('sortOrder'),
                ];
            }

            $themeName = (string)$themeElement->getAttribute('name');
            $themeLabel = (string)$themeElement->getAttribute('label');
            $themeParent = (string)$themeElement->getAttribute('parent');

            $themes[$themeName] = [
                'name' => $themeName,
                'label' => $themeLabel,
                'steps' => $steps,
                'parent' => $themeParent,
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
                $defaultColSpan = $this->defaults->getColSpan();
            }

            $defaultMdColSpan = (int)$gridLayoutElement->getAttribute('defaultMdColSpan');
            if ($defaultMdColSpan === 0) {
                $defaultMdColSpan = $this->defaults->getMdColSpan();
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

                    $blockAlias = (string)$blockElement->getAttribute('alias');
                    $blocks[$blockAlias] = [
                        'alias' => $blockAlias,
                        'sortOrder' => $sortOrder,
                        'colSpan' => $colSpan,
                        'mdColSpan' => $mdColSpan,
                    ];
                }

                $gridName = (string)$gridElement->getAttribute('name');
                $grids[$gridName] = [
                    'name' => $gridName,
                    'countryId' => (string)$gridElement->getAttribute('countryId'),
                    'parent' => (string)$gridElement->getAttribute('parent'),
                    'blocks' => $blocks,
                ];
            }

            foreach ($grids as $gridName => $grid) {
                if (empty($grid['parent'])) {
                    continue;
                }

                if (false === array_key_exists($grid['parent'], $grids)) {
                    throw new RuntimeException('Grid uses parent "'.$grid['parent'].'" which does not exist');
                }

                $gridParent = $grids[$grid['parent']];
                $grids[$gridName] = $this->merge($grid, $gridParent);
            }

            $gridName = (string)$gridLayoutElement->getAttribute('name');
            $label = (string)$gridLayoutElement->getAttribute('label');
            $countryId = (string)$gridLayoutElement->getAttribute('countryId');

            $gridLayouts[$gridName] = [
                'label' => $label,
                'countryId' => $countryId,
                'defaultSortOrder' => $defaultSortOrder,
                'defaultColSpan' => $defaultColSpan,
                'defaultMdColSpan' => $defaultMdColSpan,
                'grids' => $grids,
            ];
        }

        return $gridLayouts;
    }

    private function merge(array $child, array $parent): array
    {
        foreach ($parent as $propertyName => $propertyValue) {
            if (false === array_key_exists($propertyName, $child)) {
                $child[$propertyName] = $propertyValue;
                continue;
            }

            if (is_array($propertyValue)) {
                $propertyValue = $this->merge($child[$propertyName], $parent[$propertyName]);
                $child[$propertyName] = $propertyValue;
                continue;
            }

            if (empty($child[$propertyName])) {
                $child[$propertyName] = $propertyValue;
            }
        }

        return $child;
    }
}
