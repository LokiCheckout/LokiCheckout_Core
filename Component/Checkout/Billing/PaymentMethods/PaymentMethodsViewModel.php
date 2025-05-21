<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Api\Data\PaymentMethodInterface;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

/**
 * @method PaymentMethodsContext getContext()
 */
class PaymentMethodsViewModel extends CheckoutViewModel
{
    public function getDefaultPayment(): string
    {
        return $this->getRepository()->getDefaultPayment();
    }

    /**
     * @return PaymentMethodInterface[]
     */
    public function getPaymentMethods(): array
    {
        return $this->getRepository()->getPaymentMethods();
    }

    public function getFieldLabel(): string
    {
        return 'Payment Methods';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutPaymentMethods';
    }

    public function getFieldIdFromCode(string $code): string
    {
        return $this->getFieldId() . '-' . preg_replace('/([^a-zA-Z0-9\-]+)/', '-', $code);
    }

    public function getPaymentInstructions(string $code): string
    {
        return '';
    }
}
