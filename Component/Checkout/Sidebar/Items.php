<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar;

use Yireo\LokiCheckout\Util\Cart\ItemConvertor;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentInterface;

class Items implements ComponentInterface
{
    public function __construct(
        private CheckoutState $checkoutState,
        private ItemConvertor $itemConvertor
    ) {
    }

    public function getItems(): array
    {
        $items = [];
        foreach ($this->checkoutState->getQuote()->getItems() as $item) {
            $items[] = $this->itemConvertor->toArray($item);
        }

        if (empty($items)) {
            // @todo: Dispatch message "No items in the cart"
            // @todo: Redirect to checkout/cart
        }

        return $items;
    }
}
