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
        return (string)$this->getPropertyValue();
    }

    public function saveValue(mixed $value): void
    {
        $this->saveProperty((string)$value);
    }

    protected function getPropertyName(): string
    {
        $parts = explode('.', $this->getComponentName());
        return end($parts);
    }

    protected function getPropertyValue(mixed $defaultValue = null): mixed
    {
        $propertyName = $this->getPropertyName();
        $additionalInformation = $this->getAdditionalPaymentInformation();
        if (array_key_exists($propertyName, $additionalInformation)) {
            return $additionalInformation[$propertyName];
        }

        return $defaultValue;
    }

    protected function saveProperty(mixed $propertyValue): void
    {
        $propertyName = $this->getPropertyName();
        $quote = $this->getContext()->getCheckoutState()->getQuote();

        $additionalInformation = $quote->getPayment()->getAdditionalInformation();
        $additionalInformation[$propertyName] = $propertyValue;
        $quote->getPayment()->setAdditionalInformation($additionalInformation);

        $this->getContext()->getCheckoutState()->saveQuote($quote);
    }

    protected function getAdditionalPaymentInformation(): array
    {
        return $this->getContext()->getCheckoutState()->getQuote()->getPayment()->getAdditionalInformation();
    }
}
