<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Api\Data\PaymentMethodInterface;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

/**
 * @method PaymentMethodsContext getContext()
 * @method PaymentMethodsRepository getRepository()
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

    public function getHtmlAttributes(): array
    {
        $htmlAttributes = [];
    }
}
