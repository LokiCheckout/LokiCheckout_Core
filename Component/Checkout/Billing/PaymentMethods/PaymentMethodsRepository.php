<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Model\Quote;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method PaymentMethodsContext getContext()
 */
class PaymentMethodsRepository extends ComponentRepository
{
    public function getPaymentMethods(): array
    {
        return $this->getContext()->getPaymentMethodManagement()->getList($this->getQuote()->getId());
    }

    public function getDefaultPayment(): string
    {
        $paymentMethods = $this->getPaymentMethods();
        if (empty($paymentMethods)) {
            return '';
        }

        if (false === $this->getContext()->getModuleConfig()->selectDefaultPaymentMethod()) {
            return '';
        }

        $firstPaymentMethod = array_shift($paymentMethods);

        return (string)$firstPaymentMethod->getCode();
    }

    public function getValue(): mixed
    {
        $quote = $this->getQuote();
        $paymentMethod = (string)$quote->getPayment()->getMethod();

        if (empty($paymentMethod)) {
            $paymentMethod = $this->getDefaultPayment();
            $this->saveValue($paymentMethod);
        }

        $sanitizedPaymentMethod = $this->sanitizePaymentMethod($paymentMethod);
        if ($paymentMethod !== $sanitizedPaymentMethod) {
            $this->saveValue($sanitizedPaymentMethod);
        }

        return $sanitizedPaymentMethod;
    }

    public function saveValue(mixed $value): void
    {
        $value = $this->sanitizePaymentMethod((string)$value);
        if (empty($value)) {
            return;
        }

        $quote = $this->getQuote();
        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $value]);
        $this->getContext()->getCartRepository()->save($quote);
        $quote->collectTotals();
    }

    protected function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }

    protected function sanitizePaymentMethod(string $paymentMethod): string
    {
        if (empty($paymentMethod)) {
            return $this->getDefaultPayment();
        }

        if (false === $this->isPaymentMethodAvailable($paymentMethod)) {
            return $this->getDefaultPayment();
        }

        return $paymentMethod;
    }

    protected function isPaymentMethodAvailable(string $code): bool
    {
        foreach ($this->getPaymentMethods() as $paymentMethod) {
            if ($paymentMethod->getCode() === $code) {
                return true;
            }
        }

        $this->component->getLocalMessageRegistry()->addError('Method is not available');
        return false;
    }
}
