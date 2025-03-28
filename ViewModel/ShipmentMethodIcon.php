<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Shipment\Icon\IconResolverContext;
use Yireo\LokiCheckout\Shipment\Icon\IconResolverContextFactory;
use Yireo\LokiCheckout\Shipment\Icon\IconResolverListing;

/**
 * @api
 */
class ShipmentMethodIcon implements ArgumentInterface
{
    public function __construct(
        private IconResolverListing $iconResolverListing,
        private IconResolverContextFactory $iconResolverContextFactory,
    ) {
    }

    public function getIcon(string $paymentMethodCode): string
    {
        $context = $this->getIconResolverContext($paymentMethodCode);
        foreach ($this->iconResolverListing->getIconResolvers() as $iconResolver) {
            $icon = $iconResolver->resolve($context);
            if (is_string($icon)) {
                return $icon;
            }
        }

        return '';
    }

    private function getIconResolverContext(string $shipmentMethodCode): IconResolverContext
    {
        return $this->iconResolverContextFactory->create(['shipmentMethodCode' => $shipmentMethodCode]);
    }
}
