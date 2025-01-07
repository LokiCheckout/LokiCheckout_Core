<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldContext;
use Yireo\LokiCheckout\Util\StreetLinesFactory;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class StreetLineContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly AddressFieldContext $parentContext,
        private readonly StreetLinesFactory $streetLinesFactory,
    ) {
    }

    public function getStreetLinesFactory(): StreetLinesFactory
    {
        return $this->streetLinesFactory;
    }
}
