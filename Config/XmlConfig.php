<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Config;

use Magento\Framework\Config\Data as DataConfig;
use RuntimeException;
use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout;
use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid;
use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid\Block;
use LokiCheckout\Core\Config\XmlConfig\Value\Skin;
use LokiCheckout\Core\Config\XmlConfig\Value\Skin\Step;
use LokiCheckout\Core\Exception\UnknownSkinException;

class XmlConfig extends DataConfig
{
    /**
     * @return Skin[]
     */
    public function getSkins(): array
    {
        $skins = [];
        $skinsData = (array)$this->get('skins');
        foreach ($skinsData as $skinData) {
            if (empty($skinData)) {
                continue;
            }

            $skinName = $skinData['name'];
            if (!empty($skinData['parent'])) {
                $parentSkinData = $this->getSkinData($skinData['parent']);
                if (!empty($parentSkinData)) {
                    $skinData['steps'] = array_merge(
                        $parentSkinData['steps'],
                        $skinData['steps']
                    );
                }
            }

            $skins[$skinName] = $this->createSkin($skinData);
        }

        return $skins;
    }

    private function getSkinData(?string $skinName = null): array
    {
        $skinsData = (array)$this->get('skins');
        foreach ($skinsData as $skinData) {
            if ($skinData['name'] === $skinName) {
                return $skinData;
            }
        }

        return [];
    }

    /**
     * @param string $name
     * @return Skin
     */
    public function getSkin(string $name): Skin
    {
        $skins = $this->getSkins();
        if (isset($skins[$name])) {
            return $skins[$name];
        }

        throw new UnknownSkinException((string)__('Unknown skin "%1"', $name));
    }

    /**
     * @param array $skinData
     * @return Skin
     */
    private function createSkin(array $skinData): Skin
    {
        $steps = [];
        foreach ($skinData['steps'] as $stepData) {
            $steps[] = new Step(
                $stepData['name'],
                $stepData['blockName'],
                $stepData['sortOrder'],
            );
        }

        $skinName = $skinData['name'];
        $skinLabel = $skinData['label'];
        $skinParent = $skinData['parent'] ?? '';

        return new Skin(
            $skinName,
            $skinLabel,
            $steps,
            $skinParent
        );
    }

    /**
     * @return array[]
     */
    public function getGridLayoutArrays(): array
    {
        return (array)$this->get('gridLayouts');
    }

    /**
     * @return GridLayout[]
     */
    public function getGridLayouts(): array
    {
        $gridLayouts = [];
        foreach ($this->getGridLayoutArrays() as $gridLayoutName => $gridLayoutArray) {
            $gridLayouts[$gridLayoutName] = $this->createGridLayout($gridLayoutName, $gridLayoutArray);
        }

        return $gridLayouts;
    }

    /**
     * @param string $name
     * @return GridLayout
     */
    public function getGridLayout(string $name): GridLayout
    {
        $gridLayouts = $this->getGridLayouts();
        if (isset($gridLayouts[$name])) {
            return $gridLayouts[$name];
        }

        throw new RuntimeException((string)__('Unknown grid layout "%1"', $name));
    }

    /**
     * @param string $name
     * @param array $gridLayoutData
     * @return GridLayout
     */
    private function createGridLayout(string $name, array $gridLayoutData): GridLayout
    {
        $grids = [];
        foreach ($gridLayoutData['grids'] as $gridData) {
            $grids[] = $this->createGrid($gridData, $gridLayoutData);
        }

        return new GridLayout(
            $name,
            (string)$gridLayoutData['label'],
            (int)$gridLayoutData['defaultSortOrder'],
            (int)$gridLayoutData['defaultColSpan'],
            (int)$gridLayoutData['defaultMdColSpan'],
            $grids
        );
    }

    /**
     * @param array $gridData
     * @return Grid
     */
    private function createGrid(array $gridData, array $gridLayoutData): Grid
    {
        $blocks = [];
        foreach ($gridData['blocks'] as $blockData) {
            $blocks[] = $this->createBlock($blockData);
        }

        return new Grid(
            (string)$gridData['name'],
            (string)$gridData['countryId'],
            (string)$gridData['parent'],
            (int)$gridLayoutData['defaultSortOrder'],
            (int)$gridLayoutData['defaultColSpan'],
            (int)$gridLayoutData['defaultMdColSpan'],
            $blocks
        );
    }

    /**
     * @param array $blockData
     * @return Block
     */
    private function createBlock(array $blockData): Block
    {
        return new Block(
            (string) $blockData['alias'],
            (int)$blockData['sortOrder'],
            (int)$blockData['colSpan'],
            (int)$blockData['mdColSpan'],
        );
    }
}
