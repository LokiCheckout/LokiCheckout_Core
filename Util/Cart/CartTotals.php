<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Cart;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Quote\Api\Data\TotalsInterface;

class CartTotals implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function getTotals(): TotalsInterface
    {
        return $this->checkoutState->calculateTotals();
    }
}
