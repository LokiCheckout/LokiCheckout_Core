<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\Items;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\Cart\ItemConvertor;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ItemsContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly GenericContext $parentContext,
        private readonly ItemConvertor $itemConvertor,
    ) {
    }

    public function getItemConvertor(): ItemConvertor
    {
        return $this->itemConvertor;
    }
}
