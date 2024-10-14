<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep;

use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magewirephp\Magewire\Component;
use Override;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class PaymentMethods extends Component
{
    public array $paymentMethods = [];
    public string $value = '';

    public function __construct(
        private CheckoutState $checkoutState,
        private PaymentMethodManagementInterface $paymentMethodManagement
    ) {
    }

    public function boot()
    {
        $cartId = $this->checkoutState->getQuote()->getId();
        foreach($this->paymentMethodManagement->getList($cartId) as $paymentMethod) {
            $this->paymentMethods[$paymentMethod->getCode()] = $paymentMethod->getTitle();
        }
    }

    public function updatedValue(string $value)
    {
        // @todo: Verify that this is a correct payment
        $quote = $this->checkoutState->getQuote();
        $quote->setPaymentMethod($value);
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => $value]);
        $quote->collectTotals()->save();

    }
}
