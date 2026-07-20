<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Postcode;

use Loki\Components\Component\AbstractComponentContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Magento\Directory\Helper\Data as DirectoryData;

/**
 * @method DirectoryData getDirectoryData()
 * @mixin CheckoutContext
 */
class PostcodeContext extends AbstractComponentContext
{
}
