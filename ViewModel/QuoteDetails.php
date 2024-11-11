<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class QuoteDetails implements ArgumentInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession,
    ) {
    }

    public function getEmail(): string
    {
        return (string)$this->checkoutSession->getQuote()->getCustomerEmail();
    }

    public function getGender(): string
    {
        return (string)$this->checkoutSession->getQuote()->getCustomerGender();
    }

    public function getDob(): string
    {
        return (string)$this->checkoutSession->getQuote()->getCustomerDob();
    }
}
