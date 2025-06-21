<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\Config\Data as DataConfig;
use RuntimeException;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Grid;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Grid\Block;
use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme;
use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme\Step;
use Yireo\LokiCheckout\Exception\UnknownThemeException;

class XmlConfig extends DataConfig
{
    /**
     * @return Theme[]
     */
    public function getThemes(): array
    {
        $themes = [];
        $themesData = (array)$this->get('themes');
        foreach ($themesData as $themeData) {
            if (empty($themeData)) {
                continue;
            }

            $themeName = $themeData['name'];
            $themes[$themeName] = $this->createTheme($themeData);
        }

        return $themes;
    }

    /**
     * @param string $name
     * @return Theme
     */
    public function getTheme(string $name): Theme
    {
        $themes = $this->getThemes();
        if (isset($themes[$name])) {
            return $themes[$name];
        }

        throw new UnknownThemeException((string)__('Unknown theme "%1"', $name));
    }

    /**
     * @param array $themeData
     * @return Theme
     */
    private function createTheme(array $themeData): Theme
    {
        $steps = [];
        foreach ($themeData['steps'] as $stepData) {
            $steps[] = new Step(
                $stepData['name'],
                $stepData['blockName'],
            );
        }

        $themeName = $themeData['name'];
        $themeLabel = $themeData['label'];

        return new Theme(
            $themeName,
            $themeLabel,
            $steps
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
            $grids[] = $this->createGrid($gridData);
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
    private function createGrid(array $gridData): Grid
    {
        $blocks = [];
        foreach ($gridData['blocks'] as $blockData) {
            $blocks[] = $this->createBlock($blockData);
        }

        return new Grid(
            (string)$gridData['name'],
            (string)$gridData['countryId'],
            (string)$gridData['parent'],
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
