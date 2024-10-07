<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class Items extends Component
{
    public array $items = [];

    public function __construct(
        private CheckoutState $checkoutState,
    ) {
    }

    public function mount(): void
    {
        $this->items = $this->checkoutState->getQuote()->getItems();
    }
}
