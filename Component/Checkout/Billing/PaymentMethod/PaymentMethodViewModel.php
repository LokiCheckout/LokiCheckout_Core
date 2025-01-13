<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethod;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;
use Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods\PaymentMethodsContext;

/**
 * @method PaymentMethodsContext getContext()
 */
class PaymentMethodViewModel extends FieldViewModel
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
