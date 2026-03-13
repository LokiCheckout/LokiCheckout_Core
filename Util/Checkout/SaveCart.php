<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use DateTime;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\ResourceModel\Quote\Address as QuoteAddressResourceModel;

class SaveCart
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly QuoteAddressResourceModel $quoteAddressResourceModel,

    ) {
    }

    public function execute(Quote $quote, bool $useRepository = false): void
    {
        if ($useRepository) {
            $this->saveViaRepository($quote);
            return;
        }

        $this->saveViaQuote($quote);
    }

    private function saveViaQuote(Quote $quote): void
    {
        $shippingAddress = $quote->getShippingAddress();
        if ($shippingAddress->hasDataChanges()) {
            $shippingAddress->setUpdatedAt((new DateTime())->format('Y-m-d H:i:s'));
            $this->quoteAddressResourceModel->save($shippingAddress);
        }

        $billingAddress = $quote->getBillingAddress();
        if ($billingAddress->hasDataChanges()) {
            $billingAddress->setUpdatedAt((new DateTime())->format('Y-m-d H:i:s'));
            $this->quoteAddressResourceModel->save($billingAddress);
        }

        if ($quote->hasDataChanges()) {
            $quote->save(); // @phpstan-ignore-line
        }
    }

    private function saveViaRepository(Quote $quote)
    {
        $this->cartRepository->save($quote); // @todo: Magento 2 #38950
    }
}
