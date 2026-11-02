<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Payment\AdditionalInformation;

use Loki\Field\Component\Base\Field\FieldRepository;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;

/**
 * @method CheckoutContext getContext()
 */
abstract class AdditionalInformationRepository extends FieldRepository
{
    public function getValue(): mixed
    {
        $value = trim((string)$this->getPropertyValue(), " \f\n\r\t\v\x00");
        if (!empty($value)) {
            $this->saveValue($value);
            return $value;
        }

        $defaultValue = trim((string)$this->getDefaultValue(), " \f\n\r\t\v\x00");
        $this->saveValue($defaultValue);

        return $defaultValue;
    }

    public function saveValue(mixed $value): void
    {
        $this->saveProperty((string)$value);
    }

    public function getPropertyName(): string
    {
        $parts = explode('.', $this->getComponentName());

        return end($parts);
    }

    public function getPropertyValue(mixed $defaultValue = null, ?string $propertyName = null): mixed
    {
        if ($propertyName === null) {
            $propertyName = $this->getPropertyName();
        }

        $additionalInformation = $this->getAdditionalPaymentInformation();
        if (array_key_exists($propertyName, $additionalInformation)) {
            return $additionalInformation[$propertyName];
        }

        return $defaultValue;
    }

    protected function saveProperty(mixed $propertyValue, ?string $propertyName = null): void
    {
        if ($propertyName === null) {
            $propertyName = $this->getPropertyName();
        }

        $propertyValue = (string)$propertyValue;
        $additionalInformation = $this->getAdditionalPaymentInformation();
        if (array_key_exists($propertyName, $additionalInformation)
            && (string)$additionalInformation[$propertyName] === $propertyValue) {
            return;
        }

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $quote->getPayment()->setAdditionalInformation($propertyName, $propertyValue);
        $this->getContext()->getCheckoutState()->saveQuote($quote, true);
    }

    public function getAdditionalPaymentInformation(): array
    {
        return $this->getContext()->getCheckoutState()->getQuote()->getPayment()->getAdditionalInformation();
    }
}
