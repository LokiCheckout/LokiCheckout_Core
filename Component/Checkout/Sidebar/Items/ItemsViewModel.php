<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Items;

use LokiCheckout\Core\Config\Config;
use Magento\Quote\Api\Data\CartItemInterface;
use Loki\Components\Component\ComponentViewModel;

/**
 * @method ItemsContext getContext()
 */
class ItemsViewModel extends ComponentViewModel
{
    public function __construct(
        private Config $config
    ) {
    }

    /**
     * @return CartItemInterface[]
     */
    public function getItems(): array
    {
        $items = $this->getContext()->getCheckoutState()->getQuote()->getItems();
        return array_slice($items, 0, $this->config->getMaxCartItemsToDisplay());
    }
}
