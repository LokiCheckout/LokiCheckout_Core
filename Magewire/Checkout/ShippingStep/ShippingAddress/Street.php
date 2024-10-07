<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\FriggaCheckout\Util\Context;

class Street extends ShippingAddressFormFieldComponent implements FormTextMultipleFieldInterface
{
    const FIELD_NAME = 'values';

    const ATTRIBUTE_CODE = 'street';
    const FIELD_TYPE = 'text_multiple';

    public array $values = [];
    private int $counter = 0;

    public function __construct(
        Context $context,
        private ScopeConfigInterface $scopeConfig,
    ) {
        parent::__construct($context);
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

    public function isRequired(): bool
    {
        return true;
    }

    public function setCounter($counter = 0): void
    {
        $this->counter = $counter;
    }

    public function updated($value, string $name)
    {
        $this->context->getCheckoutState()->setShippingAddressValue($this->getAttributeCode(), $value);
        $this->emit('quoteSave');

        return $value;
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
