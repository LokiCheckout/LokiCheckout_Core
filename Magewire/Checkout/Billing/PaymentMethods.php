<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentMethodInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PaymentMethods extends Component
{
    use InjectLokiCheckoutConfig;
    use StepBehaviour;

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
        $quote = $this->checkoutState->getQuote();
        $cartId = $quote->getId();
        $this->paymentMethods = $this->paymentMethodManagement->getList($cartId);
        $this->value = (string)$quote->getPayment()->getMethod();

        if (empty($this->value) && $this->lokiCheckoutConfig->selectDefaultPaymentMethod()) {
            $this->value = $this->getDefaultPayment();
            $quote->setShippingMethod($this->value);
            $this->cartRepository->save($quote);
        }

        if (!empty($this->value)) {
            $this->valid = true;
        }
    }

    public function getDefaultPayment(): string
    {
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

    public function setValue(string $value)
    {
        // @todo: Verify that this is a correct payment
        $this->value = $value;

        $quote = $this->checkoutState->getQuote();
        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $value]);
        $this->checkoutState->saveQuote($quote);

        $this->emit('afterSaveBillingStep');
        $this->emit('afterSavePaymentMethod', $value);

        return $value;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}
