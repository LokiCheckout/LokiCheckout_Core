<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class Items extends AbstractComponent
{
    public array $items = [];

    public function mount(): void
    {
        $this->items = $this->checkoutState->getQuote()->getItems();
        parent::mount();
    }
}
