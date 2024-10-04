<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

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
