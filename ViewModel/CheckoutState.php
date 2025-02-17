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
    private int $quoteId = 0;
    private array $quotes = [];

    public function __construct(
        private CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function setQuoteId(int $quoteId): void
    {
        $this->quoteId = $quoteId;
    }

    public function getQuote(): Quote
    {
        if (empty($this->quoteId)) {
            $this->quoteId = (int)$this->checkoutSession->getQuote()->getId();
        }

        if (isset($this->quotes[$this->quoteId]) && $this->quotes[$this->quoteId] instanceof Quote) {
            return $this->quotes[$this->quoteId];
        }

        try {
            $this->quotes[$this->quoteId] = $this->cartRepository->get($this->quoteId);
        } catch (NoSuchEntityException $e) {
            $redirectUrl = $this->urlFactory->create()->getUrl('checkout/cart');
            throw new RedirectException($redirectUrl);
        }

        return $this->quotes[$this->quoteId];
    }

    public function saveQuote(Quote $quote)
    {
        $quote->collectTotals(); // @todo: Is this always needed with every single save?
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
