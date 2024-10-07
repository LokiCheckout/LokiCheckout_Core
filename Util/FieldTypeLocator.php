<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Util;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;

class FieldTypeLocator
{
    public function getFieldTypeFromComponent(Component $component): string
    {
        if ($component instanceof FormTextMultipleFieldInterface) {
            return 'text_multiple';
        }

        if ($component instanceof FormTextSelectFieldInterface) {
            return 'text_select';
        }

        if ($component instanceof FormSelectFieldInterface) {
            return 'select';
        }

        return 'text';
    }
}
