<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Util\Context;

abstract class ShippingAddressFormFieldComponent extends EavFormFieldComponent
{
    public function boot(): void
    {
        $this->value = (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getData($this->getAttributeCode());
    }

    public function updated($value, string $name)
    {
        if (false === $this->isValid()) {
            return $value;
        }

        $this->context->getCheckoutState()->setShippingAddressValue($this->getAttributeCode(), $value);
        $this->emit('quoteSave');

        return $value;
    }
}
