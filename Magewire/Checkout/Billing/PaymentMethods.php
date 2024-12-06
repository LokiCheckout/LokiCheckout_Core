<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentMethodInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PaymentMethods extends GenericComponent
{
    use InjectLokiCheckoutConfig;
    use StepBehaviour;
    use AlpineDataBehaviour;

    /** @var PaymentMethodInterface[] $paymentMethods */
    private array $paymentMethods = [];
    public string $value = '';
    public bool $valid = false;

    public function __construct(
        private CheckoutState $checkoutState,
        private PaymentMethodManagementInterface $paymentMethodManagement,
        private CartRepositoryInterface $cartRepository
    ) {
    }

    public function boot()
    {
        $cart = $this->checkoutState->getQuote();
        $cartId = $cart->getId();
        $this->paymentMethods = $this->paymentMethodManagement->getList($cartId);

        $this->value = (string)$cart->getPayment()->getMethod();

        if (false === $this->isPaymentMethodAvailable($this->value)) {
            $this->value = '';
            $cart->setPaymentMethod('');
            $this->cartRepository->save($cart);
        }

        if (empty($this->value)) {
            $this->value = $this->getDefaultPayment();
            $cart->setPaymentMethod($this->value);
            $this->cartRepository->save($cart);
        }

        if (!empty($this->value)) {
            $cart->collectTotals();
        }

        if (!empty($this->value) && $this->request->isPreceding()) {
            $this->valid = true;
        }

        parent::boot();
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

    public function setValue(string $value): void
    {
        $this->value = $value;

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

        $this->emit('afterSaveBillingStep');
        $this->emit('afterSavePaymentMethod', $value);
    }

    public function isValid(): bool
    {
        return $this->valid;
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
}
