<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use LokiCheckout\Core\Util\Checkout\SaveCart;
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
use Magento\Quote\Model\ResourceModel\Quote\Address as QuoteAddressResourceModel;

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
        private readonly SaveCart $saveCart
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
        if(false === $this->getQuoteId() > 0) {
            return false;
        }

        $quote = $this->getQuote();
        if (!$quote->hasItems() || $quote->getHasError() || !$quote->validateMinimumAmount()) {
            return false;
        }

        return true;
    }

    public function getQuoteId(): int
    {
        if ($this->quoteId === 0) {
            $this->quoteId = (int)$this->checkoutSession->getQuoteId();
        }

        if ($this->quoteId === 0) {
            $this->quoteId = (int)$this->checkoutSession->getQuote()->getId();
        }

        return $this->quoteId;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getPersistedQuote(): Quote
    {
        /** @var Quote $quote */
        $quote = $this->cartRepository->get($this->getQuoteId());
        return $quote;
    }

    public function getQuote(): Quote
    {
        $quoteId = $this->getQuoteId();
        if (isset($this->quotes[$quoteId]) && $this->quotes[$quoteId] instanceof Quote) {
            return $this->quotes[$quoteId];
        }

        $quoteFromSession = $this->checkoutSession->getQuote();
        if ($quoteFromSession instanceof Quote) {
            $this->quotes[$quoteId] = $quoteFromSession;
            return $this->quotes[$quoteId];
        }

        try {
            $this->quotes[$quoteId] = $this->getPersistedQuote();
        } catch (NoSuchEntityException $e) {
            $this->handleQuoteNotFoundException();
        }

        return $this->quotes[$quoteId];
    }

    public function setQuote(Quote $quote): void
    {
        $this->quotes[$quote->getId()] = $quote;
    }

    public function saveQuote(?Quote $quote = null, bool $useRepository = false)
    {
        if (false === $quote instanceof Quote) {
            $quote = $this->getQuote();
        }

        try {
            $this->saveCart->execute($quote, $useRepository);
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
