<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\FriggaCheckout\Util\Context;

class Street extends BillingAddressFormFieldComponent implements FormTextMultipleFieldInterface
{
    const FIELD_NAME = 'values';

    const ATTRIBUTE_CODE = 'street';
    const FIELD_TYPE = 'text_multiple';

    public array $values = [];
    private int $counter = 0;

    public function __construct(
        Validator $validator,
        Context $context,
        private ScopeConfigInterface $scopeConfig,
    ) {
        parent::__construct($validator, $context);
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

    public function setCounter($counter = 0): void
    {
        $this->counter = $counter;
    }

    public function updated($value, string $name)
    {
        $this->context->getCheckoutState()->setBillingAddressValue($this->getAttributeCode(), $value);
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
