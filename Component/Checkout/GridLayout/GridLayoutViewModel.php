<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\GridLayout;

use LokiCheckout\Core\Config\Config;
use Loki\Components\Component\ComponentViewModel;

class GridLayoutViewModel extends ComponentViewModel
{
    protected bool $skipValidation = true;

    public function __construct(
        private readonly Config $config,
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
        $grid = $this->config->getGrid($this->getBlock()->getParentBlock());

        $gridLayoutData = [];
        foreach ($grid->getBlocks() as $block) {
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
