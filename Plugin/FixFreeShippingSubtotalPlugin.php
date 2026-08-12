<?php declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use LokiCheckout\Core\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Sales\Model\Order\Total;
use Magento\Store\Model\ScopeInterface;

class FixFreeShippingSubtotalPlugin
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function afterGetShippingMethods(ShippingMethodsRepository $subject, array $shippingMethods): array
    {
        $freeShippingSubtotal = $this->scopeConfig->getValue('carriers/freeshipping/free_shipping_subtotal', ScopeInterface::SCOPE_STORE);
        $taxIncluding = (bool)$this->scopeConfig->getValue('carriers/freeshipping/tax_including', ScopeInterface::SCOPE_STORE);

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
                $taxTotals = $totals['tax'];
                if ($taxTotals instanceof Total) {
                    $subtotal += (float)$taxTotals->getValue();
                } else {
                    $subtotal += (float)$totals['tax'];
                }
            }

            if ($subtotal > $freeShippingSubtotal) {
                continue;
            }

            unset($shippingMethods[$shippingMethodId]);
        }

        return $shippingMethods;
    }
}
