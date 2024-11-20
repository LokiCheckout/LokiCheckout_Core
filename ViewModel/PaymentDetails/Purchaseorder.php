<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\PaymentDetails;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Purchaseorder implements ArgumentInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession
    ){
    }

    public function getPoNumber(): string
    {
        return (string)$this->checkoutSession->getQuote()->getPayment()->getPoNumber();
    }
}
