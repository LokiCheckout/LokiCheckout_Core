<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Shipment\Icon\IconResolverContext;
use LokiCheckout\Core\Shipment\Icon\IconResolverContextFactory;
use LokiCheckout\Core\Shipment\Icon\IconResolverListing;

/**
 * @api
 */
class ShipmentMethodIcon implements ArgumentInterface
{
    public function __construct(
        private readonly IconResolverListing $iconResolverListing,
        private readonly IconResolverContextFactory $iconResolverContextFactory,
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
