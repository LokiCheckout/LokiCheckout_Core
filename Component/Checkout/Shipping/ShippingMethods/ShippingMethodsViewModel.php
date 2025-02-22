<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

/**
 * @method ShippingMethodsContext getContext()
 * @method ShippingMethodsRepository getRepository()
 */
class ShippingMethodsViewModel extends FieldViewModel
{
    /**
     * @return ShippingMethodInterface[]
     */
    public function getShippingMethods(): array
    {
        return $this->getRepository()->getShippingMethods();
    }

    public function getFieldLabel(): string
    {
        return 'Shipping Methods';
    }

    public function isRequired(): bool
    {
        return $this->isVirtualQuote() ? false : true;
    }

    public function isAllowRendering(): bool
    {
        if ($this->isVirtualQuote()) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutShippingMethods';
    }

    private function isVirtualQuote(): bool
    {
        return $this->getContext()->getCheckoutState()->getQuote()->isVirtual();
    }
}
