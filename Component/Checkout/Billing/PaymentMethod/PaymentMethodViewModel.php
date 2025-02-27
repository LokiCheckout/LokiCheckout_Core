<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethod;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods\PaymentMethodsContext;

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
