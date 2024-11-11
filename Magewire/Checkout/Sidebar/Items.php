<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
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

    public function updateItemQty($itemId, $qty = 1): void
    {
        $quote = $this->checkoutState->getQuote();
        try {
            $quote->updateItem((int)$itemId, new DataObject(['qty' => (int)$qty]));
        } catch (LocalizedException $e) {
            $this->dispatchErrorMessage($e->getMessage());
            return;
        }

        $this->checkoutState->saveQuote($quote);

        $this->items = $this->getItems();
        $this->emit('quoteSave');
        $this->clearFlashMessages();
        $this->dispatchSuccessMessage(__('Item is updated'));
    }

    public function removeItem($itemId): void
    {
        $quote = $this->checkoutState->getQuote();
        $quote->removeItem((int)$itemId);
        $this->checkoutState->saveQuote($quote);

        $this->items = $this->getItems();
        $this->emit('quoteSave');
        $this->clearFlashMessages();
        $this->dispatchWarningMessage(__('Item is removed from cart'));
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
