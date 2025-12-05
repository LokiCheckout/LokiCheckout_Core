<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\RendererList;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartItemInterface;

class ProductRenderer implements ArgumentInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly LayoutInterface $layout,
        private readonly ImageHelper $imageHelper,
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

    /**
     * @param string $sku
     * @param int    $width
     *
     * @return string
     * @deprecated Use getImage() instead
     */
    public function getImageUrlBySku(string $sku, int $width = 75): string
    {
        try {
            $product = $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            return '';
        }

        /** @var RendererList $rendererList */
        $rendererList = $this->layout->getBlock('checkout.cart.item.renderers');
        if ($rendererList instanceof RendererList) {
            $renderer = $rendererList->getRenderer($product->getTypeId());
            $renderer->setProduct($product);
            return $renderer->toHtml();
        }

        /** @var Product $product */
        return $this->imageHelper->init($product, 'product_base_image')
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize($width)
            ->getUrl();
    }
}
