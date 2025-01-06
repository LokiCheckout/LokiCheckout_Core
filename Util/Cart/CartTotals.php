<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Cart;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class CartTotals implements ArgumentInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private CartTotalRepositoryInterface $cartTotalRepository,
    ) {
    }

    public function getDiscount(): float
    {
        $totals = $this->cartTotalRepository->get($this->checkoutState->getQuote()->getId());
        return (float)$totals->getDiscountAmount();
    }
}
