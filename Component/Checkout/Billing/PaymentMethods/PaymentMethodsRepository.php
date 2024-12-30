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
        $cart = $this->getQuote();
        $cartId = $cart->getId();
        return $this->getContext()->getPaymentMethodManagement()->getList($cartId);
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

    protected function getData(): mixed
    {
        $cart = $this->getQuote();
        $paymentMethod = (string)$cart->getPayment()->getMethod();
        $sanitizedPaymentMethod = $this->sanitizePaymentMethod($paymentMethod);

        if (empty($paymentMethod)) {
            $paymentMethod = $this->getDefaultPayment();
        }

        if ($paymentMethod !== $sanitizedPaymentMethod) {
            $this->saveData($sanitizedPaymentMethod);
        }

        return $sanitizedPaymentMethod;
    }

    protected function saveData(mixed $data): void
    {
        $data = $this->sanitizePaymentMethod((string)$data);
        if (empty($data)) {
            return;
        }

        $quote = $this->getQuote();
        $quote->setPaymentMethod($data);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $data]);
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

        $this->component->getViewModel()->getMessageManager()->addLocalError('Method is not available');
        return false;
    }
}
