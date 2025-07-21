<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\PaymentDetails;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;

class Purchaseorder implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function getPoNumber(): string
    {
        return (string)$this->checkoutState->getQuote()->getPayment()->getPoNumber();
    }
}
