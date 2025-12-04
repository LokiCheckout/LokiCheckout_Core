<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Cart;

use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Util\Ajax;
use Magento\Quote\Api\Data\TotalsInterface;

class CartTotals implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly TotalsInformationManagementInterface $totalsInformationManagement,
        private readonly TotalsInformationInterfaceFactory $totalsInformationFactory,
        private readonly Ajax $ajax
    ) {
    }

    public function getTotals(): TotalsInterface
    {
        $quote = $this->checkoutState->getQuote();

        if ($this->ajax->isAjax()) {
            $quote->setTotalsCollectedFlag(false);
        }

        $shippingAddress = $quote->getShippingAddress();
        $totalsInformation = $this->totalsInformationFactory->create();
        $totalsInformation->setAddress($shippingAddress);

        $parts = explode('_', (string)$shippingAddress->getShippingMethod());
        if (count($parts) > 1) {
            $carrierCode = array_shift($parts);
            $methodCode = implode("_", $parts);
            $totalsInformation->setShippingMethodCode($methodCode);
            $totalsInformation->setShippingCarrierCode($carrierCode);
        }

        return $this->totalsInformationManagement->calculate($quote->getId(), $totalsInformation);
    }
}
