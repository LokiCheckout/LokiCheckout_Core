<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Items;

use Yireo\LokiComponents\Component\ComponentViewModel;
/**
 * @method ItemsContext getContext()
 */
class ItemsViewModel extends ComponentViewModel
{
    public function getItems(): array
    {
        $items = [];
        foreach ($this->getContext()->checkoutState->getQuote()->getItems() as $item) {
            $items[] = $this->getContext()->itemConvertor->toArray($item);
        }

        if (empty($items)) {
            // @todo: Dispatch message "No items in the cart"
            // @todo: Redirect to checkout/cart
        }

        return $items;
    }
}
