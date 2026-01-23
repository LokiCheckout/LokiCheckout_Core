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
        $value = trim((string)$this->getPropertyValue());
        if (!empty($value)) {
            return $value;
        }

        $defaultValue = trim((string)$this->getDefaultValue());
        $this->saveValue($defaultValue);
        $this->getContext()->getCheckoutState()->saveQuote();

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

        $quote = $this->getContext()->getCheckoutState()->getQuote();

        $additionalInformation = $quote->getPayment()->getAdditionalInformation();
        $additionalInformation[$propertyName] = $propertyValue;

        $quote->getPayment()->setAdditionalInformation($additionalInformation);
    }

    public function getAdditionalPaymentInformation(): array
    {
        return $this->getContext()->getCheckoutState()->getQuote()->getPayment()->getAdditionalInformation();
    }
}
