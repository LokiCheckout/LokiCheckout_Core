<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\Config\Data as DataConfig;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Block;

class XmlConfig extends DataConfig
{
    /**
     * @return GridLayout[]
     */
    public function getGridLayouts(): array
    {
        return $this->get('gridLayouts');
    }

    public function getGridLayout(string $name): GridLayout
    {
        $gridLayouts = $this->getGridLayouts();
        if (isset($gridLayouts[$name])) {
            return $this->createGridLayout($name, $gridLayouts[$name]);
        }

        throw new \RuntimeException((string)__('Unknown grid layout "%1"', $name));
    }

    private function createGridLayout(string $name, array $gridData): GridLayout
    {
        $blocks = [];
        foreach ($gridData['blocks'] as $blockData) {
            $blocks[] = new Block(
                $blockData['alias'],
                $blockData['sortOrder'],
                $blockData['gridClass']
            );
        }

        return new GridLayout(
            $name,
            (int) $gridData['defaultSortOrder'],
            (string) $gridData['defaultGridClass'],
            $blocks
        );
    }
}
