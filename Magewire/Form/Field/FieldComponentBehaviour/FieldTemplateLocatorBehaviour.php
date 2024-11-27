<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

use Yireo\LokiCheckout\Magewire\SetterInjection\InjectFieldTemplateLocator;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;

trait FieldTemplateLocatorBehaviour
{
    use InjectFieldTemplateLocator;

    public function getFieldTemplate(): string
    {
        if (false === $this->fieldTemplateLocator instanceof FieldTemplateLocator) {
            throw new \RuntimeException('FieldTemplateLocator not set for "'.get_class($this).'"');
        }

        return $this->fieldTemplateLocator?->getFieldTemplateFromComponent($this);
    }
}
