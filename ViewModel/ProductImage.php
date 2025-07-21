<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
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
        try {
            $product = $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            return '';
        }

        /** @var Product $product */
        return $this->imageHelper->init($product, 'product_base_image')
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize($width, $width)
            ->getUrl();
    }
}
