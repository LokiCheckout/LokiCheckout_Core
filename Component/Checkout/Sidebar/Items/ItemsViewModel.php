<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Items;

use Magento\Quote\Api\Data\CartItemInterface;
use Yireo\LokiComponents\Component\ComponentViewModel;

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
