<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Override;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextMultipleFieldInterface;

class Street extends AddressFieldComponent implements FormTextMultipleFieldInterface
{
    public array $values = [];
    protected int $counter = 0;

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    #[Override]
    public function boot(): void
    {
        parent::boot();
        $this->values = explode("\n", (string)$this->value);
    }

    public function updatedValues($value)
    {
        $this->addressState->save($this->getFieldName(), implode("\n", $value));
        $this->emit('shippingStepSave');

        return $value;
    }

    public function getFieldValue(): string
    {
        return 'values';
    }

    public function getModelName(): string
    {
        return 'values.'.$this->counter;
    }

    public function isRequired(): bool
    {
        return true;
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
