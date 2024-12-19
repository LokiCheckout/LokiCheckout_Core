<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\SetterInjection;

use Yireo\LokiCheckout\Util\FieldTemplateLocator;

trait InjectFieldTemplateLocator
{
    protected ?FieldTemplateLocator $fieldTemplateLocator = null;

    public function setFieldTemplateLocator(FieldTemplateLocator $fieldTemplateLocator)
    {
        $this->fieldTemplateLocator = $fieldTemplateLocator;
    }
}
