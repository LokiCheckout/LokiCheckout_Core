<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Billing\PaymentMethod;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Component\Checkout\Billing\PaymentMethods\PaymentMethodsContext;

/**
 * @method PaymentMethodsContext getContext()
 */
class PaymentMethodViewModel extends CheckoutViewModel
{
    public function getFieldLabel(): string
    {
        return 'Payment Method';
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutPaymentMethod';
    }
}
