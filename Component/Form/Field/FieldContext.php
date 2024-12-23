<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiCheckout\Util\Component\AlpineComponentData;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class FieldContext implements ComponentContextInterface
{
    public function __construct(
        public readonly FieldTemplateLocator $fieldTemplateLocator,
        public readonly AlpineComponentData $alpineComponentData,
        public readonly StepProvider $stepProvider,
    ) {
    }
}
