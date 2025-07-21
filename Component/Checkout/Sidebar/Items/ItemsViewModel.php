<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Items;

use Magento\Quote\Api\Data\CartItemInterface;
use Loki\Components\Component\ComponentViewModel;

/**
 * @method ItemsContext getContext()
 */
class ItemsViewModel extends ComponentViewModel
{
    /**
     * @return CartItemInterface[]
     */
    public function getItems(): array
    {
        return $this->getContext()->getCheckoutState()->getQuote()->getItems();
    }
}
