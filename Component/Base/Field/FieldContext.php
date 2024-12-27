<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\Component\FieldTemplateLocator;

class FieldContext extends GenericContext
{
    public function getFieldTemplateLocator(): FieldTemplateLocator
    {
        return $this->get(FieldTemplateLocator::class);
    }
}
