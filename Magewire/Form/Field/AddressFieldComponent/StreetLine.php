<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

class StreetLine extends AddressFieldComponent
{
    protected int $index = 0;

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    protected function loadValue(): void
    {
        $street = trim((string)$this->addressState->load('street'));
        $streetLines = explode("\n", $street);
        $this->value = isset($streetLines[$this->index]) ? $streetLines[$this->index] : '';
    }

    protected function saveValue($value)
    {
        $street = (string)$this->addressState->load('street');
        $streetLines = explode("\n", $street);
        $streetLines[$this->index] = (string)$value;
        $this->addressState->save('street', implode("\n", $streetLines));
    }

    public function isVisible(): bool
    {
        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        return $streetLines > $this->index;
    }
}
