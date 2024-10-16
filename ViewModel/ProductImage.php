<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductImage implements ArgumentInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ImageHelper $imageHelper,
    ) {
    }

    public function getImageUrlBySku(string $sku, int $width = 75): string
    {
        $product = $this->productRepository->get($sku);
        return $this->imageHelper->init($product, 'product_base_image')
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize($width, $width)
            ->getUrl();
    }
}
