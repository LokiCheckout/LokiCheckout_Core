<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class FixFreeShippingSubtotalPlugin
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private CheckoutState $checkoutState
    ) {
    }

    public function afterGetShippingMethods(ShippingMethodsRepository $subject, array $shippingMethods): array
    {
        $freeShippingSubtotal = $this->scopeConfig->getValue('carriers/freeshipping/free_shipping_subtotal');
        $taxIncluding = (bool)$this->scopeConfig->getValue('carriers/freeshipping/carriers/freeshipping/tax_including');

        if (false === is_numeric($freeShippingSubtotal) && false === is_float($freeShippingSubtotal)) {
            return $shippingMethods;
        }

        foreach ($shippingMethods as $shippingMethodId => $shippingMethod) {
            if ($shippingMethod->getMethodCode() !== 'freeshipping') {
                continue;
            }

            $quote = $this->checkoutState->getQuote();
            $totals = $quote->getTotals();

            $subtotal = $quote->getSubtotal();
            if ($taxIncluding && isset($totals['tax'])) {
                $subtotal = $subtotal + $totals['tax'];
            }

            if ($subtotal > $freeShippingSubtotal) {
                continue;
            }

            unset($shippingMethods[$shippingMethodId]);
        }

        return $shippingMethods;
    }
}
