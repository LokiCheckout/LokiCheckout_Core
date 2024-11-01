<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextMultipleFieldInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextSelectFieldInterface;

class FieldTemplateLocator
{
    public function getFieldTemplateFromComponent(AddressFieldComponent $component): string
    {
        if ($component instanceof FormTextMultipleFieldInterface) {
            return 'Yireo_LokiCheckout::form/field/text_multiple.phtml';
        }

        if ($component instanceof FormTextSelectFieldInterface) {
            return 'Yireo_LokiCheckout::form/field/text_select.phtml';
        }

        if ($component instanceof FormSelectFieldInterface) {
            return 'Yireo_LokiCheckout::form/field/select.phtml';
        }

        // @todo: Radiobox
        // @todo: Checkbox
        // @todo: Textarea

        return 'Yireo_LokiCheckout::form/field/text.phtml';
    }
}
