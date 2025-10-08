<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Shipping\ShippingMethods;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

/**
 * @method ShippingMethodsContext getContext()
 * @method ShippingMethodsRepository getRepository()
 */
class ShippingMethodsViewModel extends CheckoutViewModel
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
        return !$this->isVirtualQuote();
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

    public function getJsData(): array
    {
        return [
            ...parent::getJsData(),
            'shippingMethodCount' => count($this->getShippingMethods()),
        ];
    }

    private function isVirtualQuote(): bool
    {
        return $this->getContext()->getCheckoutState()->getQuote()->isVirtual();
    }

    public function getFieldIdFromCode(string $code): string
    {
        return $this->getFieldId() . '-' . preg_replace('/([^a-zA-Z0-9\-]+)/', '-', $code);
    }
}
