<?php
declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\Data\TotalSegmentExtension;
use Magento\Quote\Model\Cart\TotalSegment;
use Magento\Store\Model\ScopeInterface;

class TaxTotal implements ArgumentInterface
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function showFullSummary(TotalSegment $total): bool
    {
        if (false === $total->getValue() > 0) {
            return false;
        }

        return $this->scopeConfig->isSetFlag('tax/cart_display/full_summary', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $total
     * @return array[]
     */
    public function getRatesFromTotal(TotalSegment $total): array
    {
        $rates = [];
        /** @var TotalSegmentExtension $extensionAttributes */
        $extensionAttributes = $total->getExtensionAttributes();
        if ($extensionAttributes && $extensionAttributes->getTaxGrandtotalDetails()) {
            foreach ($extensionAttributes->getTaxGrandtotalDetails() as $taxDetail) {
                $amount = $taxDetail->getAmount();

                foreach ($taxDetail->getRates() as $rate) {
                    $rates[] = [
                        'title' => $rate->getTitle(),
                        'percentage' => $rate->getPercent(),
                        'amount' => $amount
                    ];
                }

            }
        }

        return $rates;
    }
}
