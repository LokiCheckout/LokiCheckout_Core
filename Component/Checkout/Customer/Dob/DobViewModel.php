<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Customer\Dob;

use LokiCheckout\Core\Component\Base\QuoteField\QuoteFieldViewModel;
use LokiCheckout\Core\Validator\Component\DobValidator;

class DobViewModel extends QuoteFieldViewModel
{
    public function __construct(
        private DobValidator $dobValidator
    ) {
    }

    public function getMax(): string
    {
        return date('Y-m-d', time());
    }

    public function getValue(): string
    {
        $value = parent::getValue();
        if (empty($value)) {
            return '';
        }

        $timestamp = strtotime((string)$value);
        if (false === $timestamp) {
            return '';
        }

        return date('Y-m-d', $timestamp);
    }

    public function getFieldLabel(): string
    {
        return (string)__('Date of Birth');
    }

    public function getInputType(): string
    {
        return 'date';
    }

    public function isRequired(): bool
    {
        $showCompany = (string)$this->getContext()->getScopeConfig()->getValue('customer/address/dob_show');

        if ($showCompany === 'req') {
            return true;
        }

        return parent::isRequired();
    }

    public function getFieldAttributes(array $customAttributes = []): array
    {
        $minTimestamp = time() - 60 * 60 * 24 * 365 * $this->dobValidator->getMaximumAge($this->getComponent());
        $maxTimestamp = time() - 60 * 60 * 24 * 365 * $this->dobValidator->getMinimumAge($this->getComponent());

        return [
            ...parent::getFieldAttributes($customAttributes),
            'min' => date('Y-m-d', $minTimestamp),
            'max' => date('Y-m-d', $maxTimestamp),
        ];
    }
}
