<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Config\Data as DataConfig;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout;
use Yireo\LokiCheckout\Config\XmlConfig\Value\GridLayout\Block;
use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme;
use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme\Step;

class XmlConfig extends DataConfig
{
    /**
     * @return Theme[]
     */
    public function getThemes(): array
    {
        return $this->get('themes');
    }

    /**
     * @param string $name
     * @return Theme
     */
    public function getTheme(string $name): Theme
    {
        $themes = $this->getThemes();
        if (isset($themes[$name])) {
            return $this->createTheme($name, $themes[$name]);
        }

        throw new \RuntimeException((string)__('Unknown theme "%1"', $name));
    }

    /**
     * @param string $themeName
     * @param array $themeData
     * @return Theme
     */
    private function createTheme(string $themeName, array $themeData): Theme
    {
        $steps = [];
        foreach ($themeData['steps'] as $stepData) {
            $component = ObjectManager::getInstance()->get($stepData['component_class']);

            $steps[] = new Step(
                $stepData['name'],
                $component
            );
        }

        return new Theme(
            $themeName,
            $steps
        );
    }

    /**
     * @return GridLayout[]
     */
    public function getGridLayouts(): array
    {
        return $this->get('gridLayouts');
    }

    /**
     * @param string $name
     * @return GridLayout
     */
    public function getGridLayout(string $name): GridLayout
    {
        $gridLayouts = $this->getGridLayouts();
        if (isset($gridLayouts[$name])) {
            return $this->createGridLayout($name, $gridLayouts[$name]);
        }

        throw new \RuntimeException((string)__('Unknown grid layout "%1"', $name));
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
                $blockData['alias'],
                $blockData['sortOrder'],
                $blockData['gridClass']
            );
        }

        return new GridLayout(
            $name,
            (int)$gridData['defaultSortOrder'],
            (string)$gridData['defaultGridClass'],
            $blocks
        );
    }
}
