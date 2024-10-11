<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

trait AttributeBehaviour
{
    const ATTRIBUTE_CODE = '';

    public function getAttributeCode(): string
    {
        return static::ATTRIBUTE_CODE;
    }

    public function getAttributeLabel(): string
    {
        return (string)$this->getAttribute()->getFrontendLabel();
    }

    protected function getAttribute(): AbstractAttribute
    {
        return $this->context->getAttribute($this->getAttributeCode());
    }
}
