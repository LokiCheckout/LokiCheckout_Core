<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class FieldContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private CheckoutContext $parentContext,
        private FieldTemplateLocator $fieldTemplateLocator
    ) {
    }

    public function getFieldTemplateLocator(): FieldTemplateLocator
    {
        return $this->fieldTemplateLocator;
    }
}
