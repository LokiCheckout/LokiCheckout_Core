<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\PaymentMethods;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Loki\Components\Component\ComponentRepository;

/**
 * @method PaymentMethodsContext getContext()
 */
class PaymentMethodsRepository extends ComponentRepository
{
    public function __construct(
        private ManagerInterface $eventManager,
    ) {
    }

    public function getPaymentMethods(): array
    {
        return $this->getContext()->getPaymentMethodManagement()->getList($this->getQuote()->getId());
    }

    public function getDefaultPayment(): string
    {
        $paymentMethods = $this->getPaymentMethods();
        if ($paymentMethods === []) {
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

        $sanitizedPaymentMethod = $this->sanitizePaymentMethod($paymentMethod);
        if ($paymentMethod !== $sanitizedPaymentMethod) {
            $this->saveValue($sanitizedPaymentMethod);
        }

        return $sanitizedPaymentMethod;
    }

    public function saveValue(mixed $value): void
    {
        $value = $this->sanitizePaymentMethod((string)$value);
        $quote = $this->getQuote();

        if ($value === '' || $value === '0') {
            $quote->setPaymentMethod($value);
            $quote->getPayment()->setMethod('');
            $quote->getPayment()->setAdditionalInformation([]);
            $this->getContext()->getCheckoutState()->saveQuote($quote);
            return;
        }

        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);

        try {
            $quote->getPayment()->importData(['method' => $value]);
        } catch (LocalizedException $e) {
            $this->getGlobalMessageRegistry()->addError($e->getMessage());
            return;
        }

        $this->eventManager->dispatch('loki_checkout_payment_before_save_quote', [
            'quote' => $quote,
        ]);

        $this->getContext()->getCheckoutState()->saveQuote($quote);
        $quote->collectTotals();
    }

    protected function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }

    protected function sanitizePaymentMethod(string $paymentMethod): string
    {
        if ($paymentMethod === '' || $paymentMethod === '0') {
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
                return $paymentMethod->isAvailable($this->getContext()->getCheckoutState()->getQuote());
            }
        }

        $this->component->getLocalMessageRegistry()->addError('Method is not available');
        return false;
    }
}
