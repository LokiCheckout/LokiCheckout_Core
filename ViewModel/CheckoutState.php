<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Yireo\LokiComponents\Exception\RedirectException;

class CheckoutState implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function getQuote(): Quote
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        static $quotes = [];

        if (isset($quotes[$quoteId]) && $quotes[$quoteId] instanceof Quote) {
            return $quotes[$quoteId];
        }

        try {
            $quotes[$quoteId] = $this->cartRepository->get($quoteId);
        } catch (NoSuchEntityException $e) {
            $redirectUrl = $this->urlFactory->create()->getUrl('checkout/cart');
            throw new RedirectException($redirectUrl);
        }

        return $quotes[$quoteId];
    }

    public function saveQuote(Quote $quote)
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
