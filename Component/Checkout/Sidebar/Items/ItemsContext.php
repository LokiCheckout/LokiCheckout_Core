<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\Items;

use Loki\Components\Component\AbstractComponentContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Util\Cart\ItemConvertor;

/**
 * @method ItemConvertor getItemConvertor()
 * @mixin CheckoutContext
 */
class ItemsContext extends AbstractComponentContext
{
}
