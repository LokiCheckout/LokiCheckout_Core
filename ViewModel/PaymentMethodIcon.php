<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class PaymentMethodIcon implements ArgumentInterface
{
    public function __construct(
        private AssetRepository $assetRepository
    ) {
    }

    public function getIcon(string $paymentMethodCode): string
    {
        return '';
    }

    public function getAssetRepository(): AssetRepository
    {
        return $this->assetRepository;
    }
}
