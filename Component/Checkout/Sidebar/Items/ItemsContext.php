<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Items;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\Cart\ItemConvertor;

class ItemsContext extends GenericContext
{
    public function getItemConvertor(): ItemConvertor
    {
        return $this->get(ItemConvertor::class);
    }
}
