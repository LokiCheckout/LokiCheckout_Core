<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class Items extends Component
{
    public array|null $items = [];

    public function __construct(
        private CheckoutState $checkoutState,
    ) {
    }

    public function boot(): void
    {
        $this->items = $this->checkoutState->getQuote()->getItems();
    }

    public function removeItem(int $itemId)
    {
        $this->checkoutState->getQuote()->removeItem($itemId);
    }
}
