<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Payment\Icon\IconResolverContext;
use LokiCheckout\Core\Payment\Icon\IconResolverContextFactory;
use LokiCheckout\Core\Payment\Icon\IconResolverListing;

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
