<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Cart;

use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Util\Ajax;

class CartTotals implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly TotalsInformationManagementInterface $totalsInformationManagement,
        private readonly TotalsInformationInterfaceFactory $totalsInformationFactory,
        private readonly Ajax $ajax
    ) {
    }

    public function getTotals()
    {
        $quote = $this->checkoutState->getQuote();

        if ($this->ajax->isAjax()) {
            $quote->setTotalsCollectedFlag(false);
        }

        $shippingAddress = $quote->getShippingAddress();
        $totalsInformation = $this->totalsInformationFactory->create();
        $totalsInformation->setAddress($shippingAddress);

        $parts = explode('_', $shippingAddress->getShippingMethod());
        if (!empty($parts)) {
            $carrierCode = array_shift($parts);
            $methodCode = implode("_", $parts);
            $totalsInformation->setShippingMethodCode($methodCode);
            $totalsInformation->setShippingCarrierCode($carrierCode);
        }

        $totals = $this->totalsInformationManagement->calculate($quote->getId(), $totalsInformation);
        return $totals->getTotalSegments();
    }
}
