<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Items;

use Yireo\LokiCheckout\Util\Cart\ItemConvertor;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ItemsContext implements ComponentContextInterface
{
    public function __construct(
        public CheckoutState $checkoutState,
        public ItemConvertor $itemConvertor
    ) {
    }
}
