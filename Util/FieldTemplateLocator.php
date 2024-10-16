<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class FieldTemplateLocator
{
    public function getFieldTemplateFromComponent(Component $component): string
    {
        if ($component instanceof FormTextMultipleFieldInterface) {
            return 'Yireo_LokiCheckout::generic/field/text_multiple.phtml';
        }

        if ($component instanceof FormTextSelectFieldInterface) {
            return 'Yireo_LokiCheckout::generic/field/text_select.phtml';
        }

        if ($component instanceof FormSelectFieldInterface) {
            return 'Yireo_LokiCheckout::generic/field/select.phtml';
        }

        return 'Yireo_LokiCheckout::generic/field/text.phtml';
    }
}
