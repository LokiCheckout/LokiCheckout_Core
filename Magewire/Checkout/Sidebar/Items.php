<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Util\Cart\ItemConvertor;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class Items extends Component
{
    protected $listeners = [
        'afterSaveQuote' => 'refresh'
    ];

    public array|null $items = [];

    public function __construct(
        private CheckoutState $checkoutState,
        private ItemConvertor $itemConvertor
    ) {
    }

    public function boot(): void
    {
        $this->items = $this->getItems();

        if (empty($this->items)) {
            $this->dispatchErrorMessage(__('No items in the cart'));
            $this->redirect('checkout/cart');
        }
    }

    public function getItems(): array
    {
        $items = [];
        foreach ($this->checkoutState->getQuote()->getItems() as $item) {
            $items[] = $this->itemConvertor->toArray($item);
        }

        return $items;
    }
}
