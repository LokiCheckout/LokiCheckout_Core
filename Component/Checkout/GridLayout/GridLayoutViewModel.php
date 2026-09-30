<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\GridLayout;

use Loki\Components\Attribute\JsProperty;
use Loki\Components\Component\ComponentViewModel;
use LokiCheckout\Core\Util\Block\CssStyleParser\GridStyleParser;
use Magento\Framework\View\Element\AbstractBlock;

class GridLayoutViewModel extends ComponentViewModel
{
    protected bool $skipValidation = true;

    public function __construct(
        private readonly GridStyleParser $gridStyleParser,
    ) {
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutGridLayout';
    }

    #[JsProperty(name: 'gridLayout')]
    public function getGridLayoutData(): array
    {
        $parentBlock = $this->getBlock()->getParentBlock();
        $layout = $parentBlock->getLayout();
        $childNames = $parentBlock->getChildNames();

        $gridLayoutData = [];
        foreach ($childNames as $childName) {
            $childBlock = $layout->getBlock($childName);
            if (false === $childBlock instanceof AbstractBlock) {
                continue;
            }

            $childAlias = $layout->getElementAlias($childBlock->getNameInLayout());
            $cssStyles = $this->gridStyleParser->parse([], $childBlock);

            $gridLayoutData[] = [
                'alias' => $childAlias,
                'colSpan' => $cssStyles['--loki-col-span'],
                'mdColSpan' => $cssStyles['--loki-md-col-span'],
                'sortOrder' => $cssStyles['--loki-sort-order'],
            ];
        }

        return $gridLayoutData;
    }
}
