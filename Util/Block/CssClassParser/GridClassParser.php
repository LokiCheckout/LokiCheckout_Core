<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Block\CssClassParser;

use Magento\Framework\View\Element\AbstractBlock;
use Loki\Components\Util\Block\CssClassParser\CssClassParserInterface;

class GridClassParser implements CssClassParserInterface
{
    public function parse(array $cssClasses, string $scope, AbstractBlock $block): array
    {
        if ($scope !== 'block') {
            return $cssClasses;
        }

        return array_merge_recursive($cssClasses, [
            'grid' => 'loki-col-span',
            'sortOrder' => 'order-[--loki-order]',
        ]);
    }
}
