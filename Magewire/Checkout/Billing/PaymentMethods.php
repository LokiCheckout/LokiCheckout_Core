<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magento\Quote\Api\Data\PaymentMethodInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PaymentMethods extends Component
{
    /** @var PaymentMethodInterface[] $paymentMethods */
    private array $paymentMethods = [];
    public string $value = '';

    public function __construct(
        private CheckoutState $checkoutState,
        private PaymentMethodManagementInterface $paymentMethodManagement
    ) {
    }

    public function boot()
    {
        $cartId = $this->checkoutState->getQuote()->getId();
        $this->paymentMethods = $this->paymentMethodManagement->getList($cartId);
        $this->value = (string) $this->checkoutState->getQuote()->getPayment()->getMethod();

        if (empty($this->value)) {
            $this->value = $this->getDefaultPayment();
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

        $this->emit('billingStepSave');
        $this->emit('paymentMethodChange', $value);

        return $value;
    }
}
