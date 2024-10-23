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
        // @todo: Move this to Mollie module with DI plugin
        if (false === preg_match('/^mollie_methods_(.*)$/', $paymentMethodCode, $match)) {
            return '';
        };

        if (empty($match)) {
            return '';
        }

        $iconFileId = 'Mollie_Payment::images/methods/'.$match[1].'.svg';
        $iconUrl = $this->assetRepository->getUrl($iconFileId);

        return '<img src="'.$iconUrl.'" />';
    }
}
