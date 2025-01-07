<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

class CheckoutState implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
        private readonly CartRepositoryInterface $cartRepository
    ) {
    }

    public function getQuote(): CartInterface
    {
        static $quote = null;
        if ($quote instanceof CartInterface) {
            return $quote;
        }

        $quoteId = $this->checkoutSession->getQuoteId();
        $quote = $this->cartRepository->get($quoteId);

        return $quote;
    }

    public function saveQuote(CartInterface $quote)
    {
        $quote->collectTotals();
        $this->cartRepository->save($quote);
    }

    public function getCheckoutSession(): CheckoutSession
    {
        return $this->checkoutSession;
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }
}
