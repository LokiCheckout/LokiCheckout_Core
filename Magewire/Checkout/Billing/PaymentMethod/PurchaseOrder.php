<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing\PaymentMethod;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class PurchaseOrder extends FieldComponent
{
    protected $listeners = ['paymentMethodChange' => 'refresh'];

    public string $value = '';

    public function __construct(
        private CheckoutState $checkoutState
    ) {
    }

    public function boot(): void
    {
        $this->value = (string)$this->checkoutState->getQuote()->getPayment()->getPoNumber();
    }

    public function save($value): void
    {
        $quote = $this->checkoutState->getQuote();
        $quote->getPayment()->setPoNumber($value);
        $this->checkoutState->saveQuote($quote);
    }

    public function getFieldName(): string
    {
        return 'po_number';
    }

    public function getFieldLabel(): string
    {
        return (string)__('Purchase Order Number');
    }
}
