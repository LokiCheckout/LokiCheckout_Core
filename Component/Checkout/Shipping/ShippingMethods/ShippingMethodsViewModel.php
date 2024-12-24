<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method ShippingMethodsContext getContext()
 * @method ShippingMethodsRepository getRepository()
 */
class ShippingMethodsViewModel extends ComponentViewModel
{
    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        return $this->getRepository()->getShippingMethods();
    }

    public function getCurrentShippingCode(): string
    {
        return (string)$this->getRepository()->get();
    }

    public function isValid(): bool
    {
        return !empty($this->getCurrentShippingCode());
    }

    public function getFieldLabel(): string
    {
        return 'Shipping Methods';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function getAlpineData(): array
    {
        return [
            'value' => $this->getCurrentShippingCode()
        ];
    }
}
