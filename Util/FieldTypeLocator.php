<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Util;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\MagewireCheckout\Magewire\Generic\FormTextMultipleFieldInterface;
use Yireo\MagewireCheckout\Magewire\Generic\FormTextSelectFieldInterface;

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
