<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class FieldContext implements ComponentContextInterface
{
    public function __construct(
        public readonly FieldTemplateLocator $fieldTemplateLocator,
        public readonly CheckoutState $checkoutState,
        public readonly StepProvider $stepProvider,
    ) {
    }
}
