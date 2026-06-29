<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use LokiCheckout\Core\Payment\Icon\IconResolverContext;
use LokiCheckout\Core\Payment\Icon\IconResolverContextFactory;
use LokiCheckout\Core\Payment\Icon\IconResolverListing;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class PaymentMethodIcon implements ArgumentInterface
{
    public function __construct(
        private readonly IconResolverListing $iconResolverListing,
        private readonly IconResolverContextFactory $iconResolverContextFactory,
        private readonly bool $enabled = true,
    ) {
    }

    public function getIcon(string $paymentMethodCode, array $attributes = []): string
    {
        if (false === $this->enabled) {
            return '';
        }

        $context = $this->getIconResolverContext($paymentMethodCode, $attributes);
        foreach ($this->iconResolverListing->getIconResolvers() as $iconResolver) {
            $icon = $iconResolver->resolve($context);
            if (is_string($icon)) {
                return $icon;
            }
        }

        return '';
    }

    private function getIconResolverContext(
        string $paymentMethodCode,
        array $attributes = []
    ): IconResolverContext {
        return $this->iconResolverContextFactory->create([
            'paymentMethodCode' => $paymentMethodCode,
            'attributes' => $attributes,
        ]);
    }
}
