<?php
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\Data\TotalsInterface;

class ShippingAmount implements ArgumentInterface
{
    public function __construct(
        private readonly \Magento\Tax\Model\Config $config
    ) {
    }

    public function get(TotalsInterface $totals): float
    {
        if ($this->config->displayCartShippingInclTax()) {
            return $totals->getShippingInclTax();
        }

        return $totals->getShippingAmount();
    }
}
