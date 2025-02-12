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

    /**
     * @deprecated Just use getValue()
     */
    public function getCurrentShippingCode(): string
    {
        return (string)$this->getValue();
    }

    public function getFieldLabel(): string
    {
        return 'Shipping Methods';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function isDisabled(): bool
    {
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setShouldIgnoreValidation(false);
        $validationResult = $shippingAddress->validate();
        if ($validationResult !== true) {
            /*foreach ($validationResult as $validationError) {
                $this->getComponent()->getLocalMessageRegistry()->addWarning($this->getComponent(), (string)$validationError);
            }*/
            // @todo: Add a friendly reminder to complete the address first

            return true;
        }

        return false;
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutShippingMethods';
    }
}
