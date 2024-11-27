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
            $fields = [];
            foreach ($gridLayoutElement->getElementsByTagName('field') as $field) {
                $fields[] = [
                    'name' => (string)$field->getAttribute('name'),
                    'sortOrder' => (int)$field->getAttribute('sortOrder'),
                    'gridClass' => (string)$field->getAttribute('gridClass'),
                ];
            }

            $gridName = (string)$gridLayoutElement->getAttribute('name');
            $defaultSortOrder = (int)$gridLayoutElement->getAttribute('defaultSortOrder');
            $defaultGridClass = (string)$gridLayoutElement->getAttribute('defaultGridClass');

            $gridLayouts[$gridName] = [
                'defaultSortOrder' => !empty($defaultSortOrder) ? $defaultSortOrder : 99,
                'defaultGridClass' => !empty($defaultGridClass) ? $defaultGridClass : 'col-span-6',
                'fields' => $fields,
            ];
        }

        return $gridLayouts;
    }
}
