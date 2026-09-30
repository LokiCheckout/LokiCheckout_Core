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
    private string $defaultPaymentMethod = '';

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
        if (!empty($this->defaultPaymentMethod)) {
            return $this->defaultPaymentMethod;
        }

        $paymentMethods = $this->getPaymentMethods();
        if ($paymentMethods === []) {
            $this->defaultPaymentMethod = '';
            return $this->defaultPaymentMethod;
        }

        if (false === $this->getContext()->getModuleConfig()->selectDefaultPaymentMethod()) {
            $this->defaultPaymentMethod = '';
            return $this->defaultPaymentMethod;
        }

        $firstPaymentMethod = array_shift($paymentMethods);

        $this->defaultPaymentMethod = (string)$firstPaymentMethod->getCode();
        return $this->defaultPaymentMethod;
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

        $value = (string)$value;
        if (!$this->isEmptyPaymentMethod($value) && false === $this->isPaymentMethodAvailable($value)) {
            $this->component->getLocalMessageRegistry()->addError('Method is not available');
            return;
        }

        $value = $this->sanitizePaymentMethod($value);
        $quote = $this->getQuote();

        if ($this->isEmptyPaymentMethod($value)) {
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
        if ($this->isEmptyPaymentMethod($paymentMethod)) {
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

        return false;
    }

    private function isEmptyPaymentMethod(string $paymentMethod): bool
    {
        return $paymentMethod === '' || $paymentMethod === '0';
    }
}
