<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Cart;

use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class CartTotals implements ArgumentInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private TotalsInformationManagementInterface $totalsInformationManagement,
        private TotalsInformationInterfaceFactory $totalsInformationFactory
    ) {
    }

    public function getTotals()
    {
        $quote = $this->checkoutState->getQuote();
        $totals = $this->totalsInformationFactory->create();
        $totals->setAddress($quote->getShippingAddress());

        $shippingMethod = $quote->getShippingAddress()?->getShippingMethod();
        if (!empty($shippingMethod)) {
            list($carrierCode, $carrierMethod) = explode('_', $shippingMethod);
            $totals->setShippingCarrierCode($carrierCode);
            $totals->setShippingMethodCode($carrierMethod);
        }

        $totals = $this->totalsInformationManagement->calculate($quote->getId(), $totals);
        return $totals->getTotalSegments();
    }
}

