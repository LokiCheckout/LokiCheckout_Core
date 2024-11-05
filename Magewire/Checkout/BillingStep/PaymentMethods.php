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

    public function setValue(string $value)
    {
        // @todo: Verify that this is a correct payment
        $quote = $this->checkoutState->getQuote();
        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $value]);
        $this->checkoutState->saveQuote($quote);

        $this->emit('billingStepSave');

        return $value;
    }

    public function getJsName(): string
    {
        $name = ucwords($this->getParent()->getNameInLayout(), '_.-');

        return preg_replace('#[_.-]+#', '', $name);
    }
}
