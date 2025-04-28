<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\GridLayout;

use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiComponents\Component\ComponentViewModel;

class GridLayoutViewModel extends ComponentViewModel
{
    public function __construct(
        private readonly Config $config,
        private readonly XmlConfig $xmlConfig
    ) {
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutGridLayout';
    }

    public function getJsData(): array
    {
        return [
            ...parent::getJsData(),
            'gridLayout' => $this->getGridLayoutData(),
        ];
    }

    private function getGridLayoutData(): array
    {
        $gridLayoutName = $this->config->getCurrentGridLayoutName();
        $gridLayout = $this->xmlConfig->getGridLayout($gridLayoutName);

        $gridLayoutData = [];
        foreach ($gridLayout->getBlocks() as $block) {
            $gridLayoutData[] = [
                'alias' => $block->getAlias(),
                'colSpan' => $block->getColSpan(),
                'mdColSpan' => $block->getMdColSpan(),
                'sortOrder' => $block->getSortOrder(),
            ];
        }

        return $gridLayoutData;
    }
}
