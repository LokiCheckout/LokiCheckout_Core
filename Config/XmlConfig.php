<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\Config\Data as DataConfig;
use RuntimeException;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Block;
use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme;
use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme\Step;
use Yireo\LokiCheckout\Exception\UnknownThemeException;

class XmlConfig extends DataConfig
{
    private array $gridLayouts = [];

    /**
     * @return Theme[]
     */
    public function getThemes(): array
    {
        $themes = [];
        $themesData = $this->get('themes');
        foreach ($themesData as $themeData) {
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
     * @param string $themeName
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
        return $this->get('gridLayouts');
    }

    /**
     * @return GridLayout[]
     */
    public function getGridLayouts(): array
    {
        if ($this->gridLayouts !== []) {
            return $this->gridLayouts;
        }

        foreach ($this->getGridLayoutArrays() as $gridLayoutName => $gridLayoutArray) {
            $this->gridLayouts[$gridLayoutName] = $this->createGridLayout($gridLayoutName, $gridLayoutArray);
        }

        return $this->gridLayouts;
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
     * @param array $gridData
     * @return GridLayout
     */
    private function createGridLayout(string $name, array $gridData): GridLayout
    {
        $blocks = [];
        foreach ($gridData['blocks'] as $blockData) {
            $blocks[] = new Block(
                (string) $blockData['alias'],
                isset($blockData['sortOrder']) ? (int)$blockData['sortOrder']: 99,
                isset($blockData['colSpan']) ? (int)$blockData['colSpan'] : 12,
                isset($blockData['mdColSpan']) ? (int)$blockData['mdColSpan'] : 6,
            );
        }

        return new GridLayout(
            $name,
            (string)$gridData['label'],
            (string)$gridData['countryId'],
            (int)$gridData['defaultSortOrder'],
            (int)$gridData['defaultColSpan'],
            (int)$gridData['defaultMdColSpan'],
            $blocks
        );
    }
}
