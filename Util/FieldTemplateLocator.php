<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Util;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class FieldTemplateLocator
{
    public function getFieldTemplateFromComponent(Component $component): string
    {
        if ($component instanceof FormTextMultipleFieldInterface) {
            return 'Yireo_FriggaCheckout::generic/field/text_multiple.phtml';
        }

        if ($component instanceof FormTextSelectFieldInterface) {
            return 'Yireo_FriggaCheckout::generic/field/text_select.phtml';
        }

        if ($component instanceof FormSelectFieldInterface) {
            return 'Yireo_FriggaCheckout::generic/field/select.phtml';
        }

        return 'Yireo_FriggaCheckout::generic/field/text.phtml';
    }
}
