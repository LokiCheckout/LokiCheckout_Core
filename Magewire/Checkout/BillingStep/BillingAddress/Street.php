<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\LokiCheckout\Util\Context;

class Street extends BillingAddressFieldComponent implements FormTextMultipleFieldInterface
{
    public array $values = [];
    private int $counter = 0;

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function boot(): void
    {
        parent::boot();
        $this->values = explode("\n", (string)$this->value);
    }

    public function getFieldName(): string
    {
        return 'values.'.$this->counter;
    }

    public function getFieldValue(): string
    {
        return 'values';
    }

    public function setCounter($counter = 0): void
    {
        $this->counter = $counter;
    }

    public function getTextCount(): int
    {
        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        if ($streetLines > 1) {
            return $streetLines;
        }

        return 1;
    }
}
