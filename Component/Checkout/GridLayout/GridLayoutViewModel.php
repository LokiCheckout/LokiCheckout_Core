<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\GridLayout;

use LokiCheckout\Core\Config\Config;
use Loki\Components\Component\ComponentViewModel;
use LokiCheckout\Core\Exception\BlockNotFoundException;
use Magento\Framework\View\Element\AbstractBlock;

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
        $parentBlock = $this->getBlock()->getParentBlock();
        $layout = $parentBlock->getLayout();
        $childNames = $parentBlock->getChildNames();
        $grid = $this->config->getGrid($this->getBlock()->getParentBlock());

        $gridLayoutData = [];
        foreach ($childNames as $childName) {
            $childBlock = $layout->getBlock($childName);
            if (false === $childBlock instanceof AbstractBlock) {
                continue;
            }

            $childAlias = $layout->getElementAlias($childBlock->getNameInLayout());

            try {
                $gridLayoutBlock = $grid->getBlock($childAlias);
            } catch (BlockNotFoundException $blockNotFoundException) {
                $gridLayoutData[] = [
                    'alias' => $childAlias,
                    'colSpan' => $childBlock->getColSpan() ?? $grid->getDefaultColSpan(),
                    'mdColSpan' => $childBlock->getMdColSpan() ?? $grid->getDefaultMdColSpan(),
                    'sortOrder' => $childBlock->getSortOrder() ?? $grid->getDefaultSortOrder(),
                ];

                continue;
            }

            $gridLayoutData[] = [
                'alias' => $gridLayoutBlock->getAlias(),
                'colSpan' => $gridLayoutBlock->getColSpan(),
                'mdColSpan' => $gridLayoutBlock->getMdColSpan(),
                'sortOrder' => $gridLayoutBlock->getSortOrder(),
            ];
        }

        return $gridLayoutData;
    }
}
