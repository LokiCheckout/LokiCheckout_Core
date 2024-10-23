<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\BillingStep;

use Magento\Quote\Api\Data\PaymentMethodInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magewirephp\Magewire\Component;
use Override;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PaymentMethods extends Component
{
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
        $this->value =(string) $this->checkoutState->getQuote()->getPayment()->getMethod();
        $this->paymentMethods = $this->paymentMethodManagement->getList($cartId);
    }

    /**
     * @return PaymentMethodInterface[]
     */
    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    public function updatedValue(string $value)
    {
        // @todo: Verify that this is a correct payment
        $quote = $this->checkoutState->getQuote();
        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $value]);
        $quote->collectTotals()->save();

        $this->emit('billingStepSave');

        return $value;
    }
}
