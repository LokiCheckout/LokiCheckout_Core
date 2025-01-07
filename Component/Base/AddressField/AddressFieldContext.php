<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\AddressField;

use Yireo\LokiCheckout\Component\Base\Field\FieldContext;
use Yireo\LokiCheckout\Util\Component\AddressStateProvider;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class AddressFieldContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private FieldContext $parentContext,
        private AddressStateProvider $addressStateProvider,
    ) {
    }

    public function getAddressStateProvider(): AddressStateProvider
    {
        return $this->addressStateProvider;
    }
}
