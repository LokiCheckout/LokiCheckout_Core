<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentMethodInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Yireo\LokiCheckout\Component\Form\Field\Field;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;

use Yireo\LokiCheckout\Component\Form\Field\FieldViewModel;
use Yireo\LokiCheckout\Component\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ViewModel extends FieldViewModel
{
    use InjectLokiCheckoutConfig;
    use StepBehaviour;

    /** @var PaymentMethodInterface[] $paymentMethods */
    private array $paymentMethods = [];

    public function __construct(
        private CheckoutState $checkoutState,
        private PaymentMethodManagementInterface $paymentMethodManagement,
        private CartRepositoryInterface $cartRepository
    ) {
    }

    public function initialize(): void
    {
        if ($this->isLazyload()) {
            return;
        }

        $cart = $this->checkoutState->getQuote();
        $cartId = $cart->getId();
        $this->paymentMethods = $this->paymentMethodManagement->getList($cartId);

        $this->value = (string)$cart->getPayment()->getMethod();
        $this->sanityChecks();

        if (false === $this->isPaymentMethodAvailable($this->value) && $cart->getPaymentMethod()) {
            $this->value = '';
            $cart->setPaymentMethod('');
            $this->cartRepository->save($cart);
        }

        if (empty($this->value) && $cart->getPaymentMethod()) {
            $this->value = $this->getDefaultPayment();
            $cart->setPaymentMethod($this->value);
            $this->cartRepository->save($cart);
        }

        if (!empty($this->value)) {
            $cart->collectTotals();
        }

        parent::initialize();
    }

    public function getDefaultPayment(): string
    {
        if (empty($this->paymentMethods)) {
            return '';
        }

        if (false === $this->lokiCheckoutConfig->selectDefaultPaymentMethod()) {
            return '';
        }

        $firstPaymentMethod = array_shift($this->paymentMethods);

        return (string)$firstPaymentMethod->getCode();
    }

    /**
     * @return PaymentMethodInterface[]
     */
    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    public function save($value): void
    {
        if (false === $this->isPaymentMethodAvailable($value)) {
            $this->valid = false;
            $this->value = '';
            return;
        }

        $this->valid = true;

        $quote = $this->checkoutState->getQuote();
        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $value]);
        $this->checkoutState->saveQuote($quote);
    }

    private function isPaymentMethodAvailable(string $code): bool
    {
        foreach ($this->paymentMethods as $paymentMethod) {
            if ($paymentMethod->getCode() === $code) {
                return true;
            }
        }

        return false;
    }

    public function getFieldLabel(): string
    {
        return 'Payment Method';
    }

    public function isRequired(): bool
    {
        return true;
    }

    private function sanityChecks(): void
    {
        if (empty($this->value)) {
            return;
        }

        $cart = $this->checkoutState->getQuote();
        if ($this->isPaymentMethodAvailable($this->value)) {
            return;
        }

        if (!$cart->getPaymentMethod()) {
            return;
        }

        $this->value = $this->getDefaultPayment();
        $cart->setPaymentMethod('');
        $this->cartRepository->save($cart);
    }
}
