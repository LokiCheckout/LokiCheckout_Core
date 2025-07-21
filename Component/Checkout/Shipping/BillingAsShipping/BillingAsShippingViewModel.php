<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Shipping\BillingAsShipping;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

class BillingAsShippingViewModel extends CheckoutViewModel
{
    public function getValue(): mixed
    {
        return parent::getValue();
    }

    public function getFieldLabel(): string
    {
        return 'Shipping as Billing';
    }
}
