<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\BillingAsShipping;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

class BillingAsShippingViewModel extends FieldViewModel
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
