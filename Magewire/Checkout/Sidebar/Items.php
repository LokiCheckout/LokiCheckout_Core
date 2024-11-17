<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar;

use Magento\Quote\Api\Data\CartItemInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class Items extends Component
{
    protected $listeners = [
        'quoteSave' => 'refresh'
    ];

    public array|null $items = [];

    public function __construct(
        private CheckoutState $checkoutState,
    ) {
    }

    public function boot(): void
    {
        $this->items = $this->getItems();
    }

    public function getItems(): array
    {
        $items = [];
        foreach ($this->checkoutState->getQuote()->getItems() as $item) {
            /** @var CartItemInterface $item */
            $items[] = [
                'id' => $item->getId(),
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'qty' => $item->getQty(),
            ];
        }

        return $items;
    }
}
