<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Tax\Model\Config as TaxConfig;

class PriceFormatter implements ArgumentInterface
{
    public function __construct(
        private readonly TaxConfig $taxConfig,
        private readonly PriceCurrencyInterface $priceCurrency
    ) {
    }

    public function format($price): string
    {
        return $this->priceCurrency->format($price);
    }

    public function displayCartPricesInclTax(): bool
    {
        return $this->taxConfig->displayCartPricesInclTax();
    }

    public function displayCartPricesExclTax(): bool
    {
        return $this->taxConfig->displayCartPricesExclTax();
    }

    public function displayCartPricesBoth(): bool
    {
        return $this->taxConfig->displayCartPricesBoth();
    }
}
