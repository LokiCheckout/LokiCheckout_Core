<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Yireo\LokiCheckout\Util\FieldTemplateLocator;

trait FieldTemplateLocatorBehaviour
{
    protected ?FieldTemplateLocator $fieldTemplateLocator = null;

    public function setFieldTemplateLocator(FieldTemplateLocator $fieldTemplateLocator)
    {
        $this->fieldTemplateLocator = $fieldTemplateLocator;
    }

    public function getFieldTemplate(): string
    {
        if ($fieldTemplate = $this->getFieldTemplateFromParentBlock()) {
            return $fieldTemplate;
        }

        if (false === $this->fieldTemplateLocator instanceof FieldTemplateLocator) {
            throw new \RuntimeException('FieldTemplateLocator not set for "'.get_class($this).'"');
        }

        $fieldTemplate = $this->fieldTemplateLocator?->getFieldTemplateFromComponent($this);
        if (!empty($fieldTemplate)) {
            return $fieldTemplate;
        }

        throw new \RuntimeException('Unable to locate field template for "'.get_class($this).'"');
    }

    protected function getFieldTemplateFromParentBlock(): string|false
    {
        $parentBlock = $this->getParent();
        if (empty($parentBlock)) {
            return false;
        }

        $fieldTemplate = $parentBlock->getFieldTemplate();
        if (empty($fieldTemplate)) {
            return false;
        }

        return $fieldTemplate;
    }
}
