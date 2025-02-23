<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Cart;

use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Util\Ajax;

class CartTotals implements ArgumentInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private TotalsInformationManagementInterface $totalsInformationManagement,
        private TotalsInformationInterfaceFactory $totalsInformationFactory,
        private Ajax $ajax
    ) {
    }

    public function getTotals()
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if ($this->ajax->isAjax()) {
            $quote->collectTotals();
            $shippingAddress->setCollectShippingRates(true);
            $shippingAddress->collectShippingRates();
        }

        $totals = $this->totalsInformationFactory->create();
        $totals->setAddress($shippingAddress);

        $shippingMethod = $shippingAddress->getShippingMethod();
        if (!empty($shippingMethod)) {
            list($carrierCode, $carrierMethod) = explode('_', $shippingMethod);
            $totals->setShippingCarrierCode($carrierCode);
            $totals->setShippingMethodCode($carrierMethod);
        }

        $totals = $this->totalsInformationManagement->calculate($quote->getId(), $totals);
        return $totals->getTotalSegments();
    }
}

