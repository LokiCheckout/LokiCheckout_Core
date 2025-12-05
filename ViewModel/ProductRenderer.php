<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Catalog\Helper\Product\Configuration;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\RendererList;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartItemInterface;

class ProductRenderer implements ArgumentInterface
{
    public function __construct(
        private readonly LayoutInterface $layout,
        private readonly Configuration $productConfigurationHelper,
        private readonly array $productOptionParams = []
    ) {
    }

    public function getRenderer(CartItemInterface $item): ?AbstractBlock
    {
        /** @var RendererList $rendererList */
        $rendererList = $this->layout->getBlock('checkout.cart.item.renderers');
        if (false === $rendererList instanceof RendererList) {
            return null;
        }

        $renderer = $rendererList->getRenderer($item->getProductType());
        $renderer->setItem($item);
        return $renderer;
    }

    public function getProductOptions(CartItemInterface $item): array
    {
        $params = $this->productOptionParams;

        $formattedOptions = [];
        foreach ($this->productConfigurationHelper->getOptions($item) as $option) {
            $option['formatted'] = $this->productConfigurationHelper->getFormattedOptionValue($option, $params);
            $formattedOptions[] = $option;
        }

        return $formattedOptions;
    }

    public function getImage(CartItemInterface $item, int $width = 75, int $height = 75): string
    {
        $renderer = $this->getRenderer($item);
        if ($renderer instanceof AbstractBlock) {
            return $renderer->getImage($renderer->getProductForThumbnail(), 'cart_page_product_thumbnail')
                ->setTemplate('Magento_Catalog::product/image.phtml')
                ->setWidth($width.'px')
                ->setHeight($height.'px')
                ->toHtml();
        }

        return 'unknown';
    }
}
