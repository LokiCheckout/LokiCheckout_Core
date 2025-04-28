<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\PaymentDetails;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class Purchaseorder implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState
    ){
    }

    public function getPoNumber(): string
    {
        return (string)$this->checkoutState->getQuote()->getPayment()->getPoNumber();
    }
}
