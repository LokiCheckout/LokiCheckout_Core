<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\ShippingAsBilling;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

class ShippingAsBillingViewModel extends FieldViewModel
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
