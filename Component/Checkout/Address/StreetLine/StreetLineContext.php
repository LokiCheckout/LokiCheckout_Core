<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\StreetLine;

use Loki\Components\Component\AbstractComponentContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Magento\Framework\Message\ManagerInterface as CoreMessageManager;
use LokiCheckout\Core\Util\Component\AddressStateProvider;
use LokiCheckout\Core\Util\StreetLinesFactory;
use Loki\Components\Messages\GlobalMessageRegistry;

/**
 * @method StreetLinesFactory getStreetLinesFactory()
 * @method AddressStateProvider getAddressStateProvider()
 * @method GlobalMessageRegistry getGlobalMessageRegistry()
 * @method CoreMessageManager getCoreMessageManager()
 * @mixin CheckoutContext
 */
class StreetLineContext extends AbstractComponentContext
{
}
