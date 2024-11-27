<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class FieldTemplateLocator
{
    public function getFieldTemplateFromComponent(FieldComponent $component): string
    {
        $parentBlock = $component->getParent();
        if (false === $parentBlock instanceof AbstractBlock) {
            return $this->getFieldTemplateByType('text');
        }

        $fieldTemplate = $parentBlock->getFieldTemplate();
        if (false === empty($fieldTemplate)) {
            return $fieldTemplate;
        }

        $fieldType = $parentBlock->getFieldType();
        if (array_key_exists($fieldType, $this->getFieldTemplateMapping())) {
            return $this->getFieldTemplateByType($fieldType);
        }

        return $this->getFieldTemplateByType('text');
    }

    public function getFieldTemplateByType(string $type): string
    {
        return $this->getFieldTemplateMapping()[$type];
    }

    public function getFieldTemplateMapping(): array
    {
        // @todo: Radiobox
        // @todo: Checkbox
        // @todo: Textarea

        return [
            'text' => 'Yireo_LokiCheckout::form/field/text.phtml',
            'radio' => 'Yireo_LokiCheckout::form/field/radio.phtml',
            'select' => 'Yireo_LokiCheckout::form/field/select.phtml',
            'text_select' => 'Yireo_LokiCheckout::form/field/text_select.phtml',
        ];
    }
}
