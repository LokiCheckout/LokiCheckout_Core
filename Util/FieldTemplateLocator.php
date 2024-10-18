<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;

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

        if ($component->hasClientSideValidation()) {
            return 'Yireo_LokiCheckout::form/field/text_js.phtml';
        }

        return 'Yireo_LokiCheckout::form/field/text.phtml';
    }
}
