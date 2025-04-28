<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Payment\Icon\IconResolverContext;
use Yireo\LokiCheckout\Payment\Icon\IconResolverContextFactory;
use Yireo\LokiCheckout\Payment\Icon\IconResolverListing;

/**
 * @api
 */
class PaymentMethodIcon implements ArgumentInterface
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

    private function getIconResolverContext(string $paymentMethodCode): IconResolverContext
    {
        return $this->iconResolverContextFactory->create(['paymentMethodCode' => $paymentMethodCode]);
    }
}
