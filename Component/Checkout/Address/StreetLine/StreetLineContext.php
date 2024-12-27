<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldContext;
use Yireo\LokiCheckout\Util\StreetLinesFactory;

class StreetLineContext extends AddressFieldContext
{
    public function getStreetLinesFactory(): StreetLinesFactory
    {
        return $this->get(StreetLinesFactory::class);
    }
}
