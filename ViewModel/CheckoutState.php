<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Loki\Components\Exception\RedirectException;
use Loki\Components\Util\Ajax;

class CheckoutState implements ArgumentInterface
{
    private int $quoteId = 0;
    private array $quotes = [];

    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId,
        private readonly UrlFactory $urlFactory,
        private readonly ResponseFactory $responseFactory,
        private readonly Ajax $ajax,
    ) {
    }

    public function reset(): void
    {
        $this->quotes = [];
    }

    public function setQuoteId(int $quoteId): void
    {
        $this->quoteId = $quoteId;
    }

    public function getMaskedQuoteId(): string
    {
        return $this->quoteIdToMaskedQuoteId->execute((int)$this->getQuote()->getId());
    }

    public function hasQuote(): bool
    {
        if (!$this->checkoutSession->getQuote()->getId()) {
            return false;
        }

        return true;
    }

    public function getQuote(): Quote
    {
        if ($this->quoteId === 0) {
            $this->quoteId = (int)$this->checkoutSession->getQuote()->getId();
        }

        if ($this->quoteId === 0) {
            $this->quoteId = (int)$this->checkoutSession->getQuoteId();
        }

        if (empty($this->quoteId)) {
            $this->handleQuoteNotFoundException();
        }

        if (isset($this->quotes[$this->quoteId]) && $this->quotes[$this->quoteId] instanceof Quote) {
            return $this->quotes[$this->quoteId];
        }

        try {
            $this->quotes[$this->quoteId] = $this->cartRepository->get($this->quoteId);
        } catch (NoSuchEntityException $e) {
            $this->handleQuoteNotFoundException();
        }

        return $this->quotes[$this->quoteId];
    }

    public function setQuote(Quote $quote): void
    {
        $this->quotes[$quote->getId()] = $quote;
    }

    public function saveQuote(Quote $quote)
    {
        try {
            //$this->cartRepository->save($quote); // @todo: Magento 2 #38950
            $quote->save();
        } catch (NoSuchEntityException $e) {
            $this->handleQuoteNotFoundException();
        }
    }

    public function getCheckoutSession(): CheckoutSession
    {
        return $this->checkoutSession;
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    private function handleQuoteNotFoundException(): void
    {
        $redirectUrl = $this->urlFactory->create()->getUrl('checkout/cart');

        if ($this->ajax->isAjax()) {
            throw new RedirectException($redirectUrl);
        }

        /** @var HttpResponse $response */
        $response = $this->responseFactory->create();
        $response->setRedirect($redirectUrl);
        $response->sendResponse();
        exit; // phpcs:ignore
    }
}
