<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\PaymentMethods;

use Magento\Checkout\Api\Exception\PaymentProcessingRateLimitExceededException;
use Magento\Checkout\Api\PaymentProcessingRateLimiterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Quote\Api\Data\PaymentInterfaceFactory;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Model\Quote;
use Loki\Components\Component\ComponentRepository;

/**
 * @method PaymentMethodsContext getContext()
 */
class PaymentMethodsRepository extends ComponentRepository
{
    public function __construct(
        private PaymentMethodManagementInterface $paymentMethodManagement,
        private PaymentInterfaceFactory $paymentFactory,
        private readonly ManagerInterface $eventManager,
        private ?PaymentProcessingRateLimiterInterface $paymentRateLimiter = null,
    ) {
    }

    public function getPaymentMethods(): array
    {
        return $this->getContext()->getPaymentMethodManagement()->getList($this->getQuote()->getId());
    }

    public function getDefaultPayment(): string
    {
        static $defaultPayment = false;
        if (is_string($defaultPayment)) {
            return $defaultPayment;
        }

        $paymentMethods = $this->getPaymentMethods();
        if ($paymentMethods === []) {
            $defaultPayment = '';
            return $defaultPayment;
        }

        if (false === $this->getContext()->getModuleConfig()->selectDefaultPaymentMethod()) {
            $defaultPayment = '';
            return $defaultPayment;
        }

        $firstPaymentMethod = array_shift($paymentMethods);

        $defaultPayment = (string)$firstPaymentMethod->getCode();
        return $defaultPayment;
    }

    public function getValue(): mixed
    {
        $quote = $this->getQuote();
        $currentPaymentMethod = (string)$quote->getPayment()->getMethod();

        $sanitizedPaymentMethod = $this->sanitizePaymentMethod($currentPaymentMethod);
        if ($currentPaymentMethod !== $sanitizedPaymentMethod) {
            $this->saveValue($sanitizedPaymentMethod);
        }

        return $sanitizedPaymentMethod;
    }

    public function saveValue(mixed $value): void
    {
        if ($this->paymentRateLimiter instanceof PaymentProcessingRateLimiterInterface) {
            try {
                $this->paymentRateLimiter->limit();
            } catch (PaymentProcessingRateLimitExceededException $exception) {
                $this->getContext()->getCoreMessageManager()->addErrorMessage($exception->getMessage());
                return;
            }
        }

        $value = $this->sanitizePaymentMethod((string)$value);

        if (false === $this->isPaymentMethodAvailable($value)) {
            $this->getContext()->getCoreMessageManager()->addErrorMessage(__('Payment method "%1" is not available.', $value));
            return;
        }

        $quote = $this->getQuote();

        if ($value === '' || $value === '0') {
            $quote->setPaymentMethod($value);
            $quote->getPayment()->setMethod('');
            $quote->getPayment()->setAdditionalInformation([]);
            $this->getContext()->getCheckoutState()->saveQuote($quote);
            return;
        }

        if ($quote->getPaymentMethod() !== $value) {
            $quote->getPayment()->setAdditionalInformation([]);
        }

        $payment = $this->paymentFactory->create();
        $payment->setMethod($value);
        $payment->setQuote($quote);
        $payment->importData([
            'method' => $value
        ]);

        $this->paymentMethodManagement->set($quote->getId(), $payment);

        $this->eventManager->dispatch('loki_checkout_payment_method_update', [
            'method' => $value
        ]);
    }

    protected function getPaymentMethodByCode(string $code): \Magento\Payment\Model\MethodInterface
    {
        foreach ($this->getPaymentMethods() as $paymentMethod) {
            if ($paymentMethod->getCode() === $code) {
                return $paymentMethod;
            }
        }

        throw new InvalidArgumentException(__('Payment method "%1" does not exist.', $code));
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
